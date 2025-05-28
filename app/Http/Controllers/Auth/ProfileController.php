<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Auth as LaravelAuth; // Laravel Auth facade for session user
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $uid = Session::get('uid');
    $user = app('firebase.auth')->getUser($uid);
    return view('auth.profile', compact('user'));
  }

  public function update(Request $request, $id)
  {
    $auth = app('firebase.auth');
    $user = $auth->getUser($id);

    try {
      if ($request->new_password == '' && $request->new_confirm_password == '') {
        $request->validate([
          'displayName' => 'required|min:3|max:12',
          'email' => 'required|email',
        ]);
        $properties = [
          'displayName' => $request->displayName,
          'email' => $request->email,
        ];
        $updatedUser = $auth->updateUser($id, $properties);

        if ($user->email != $request->email) {
          $auth->updateUser($id, ['emailVerified' => false]);
        }

        Session::flash('message', 'Profile Updated');
        return back()->withInput();
      } else {
        $request->validate([
          'new_password' => 'required|max:18|min:8',
          'new_confirm_password' => 'same:new_password',
        ]);
        $updatedUser = $auth->changeUserPassword($id, $request->new_password);
        Session::flash('message', 'Password Updated');
        return back()->withInput();
      }
    } catch (\Exception $e) {
      Session::flash('error', $e->getMessage());
      return back()->withInput();
    }
  }

  /**
   * New method to verify credentials before deleting.
   */
  public function verifyDelete(Request $request, $id)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|string',
    ]);

    // Get logged-in user's Firebase data
    $firebaseAuth = app('firebase.auth');
    $firebaseUser = $firebaseAuth->getUser($id);

    // Get Laravel Auth user for password verification
    $laravelUser = LaravelAuth::user();

    // Check if email matches
    if ($request->email !== $firebaseUser->email) {
      return back()->with('error', 'Email does not match our records.');
    }

    // Check password — since you use Firebase Auth, you *cannot* verify password directly here,
    // unless you keep password in Laravel or do re-authentication via Firebase (complex).
    // If Laravel Auth is managing session and password, use Laravel password verify:
    if (!Hash::check($request->password, $laravelUser->password)) {
      return back()->with('error', 'Password is incorrect.');
    }

    // Credentials verified — proceed to delete account (disable user in Firebase)
    try {
      $firebaseAuth->disableUser($id);
    } catch (\Exception $e) {
      return back()->with('error', 'Failed to delete account: ' . $e->getMessage());
    }

    // Logout user from Laravel session and Firebase session
    LaravelAuth::logout();
    Session::flush();

    return redirect('/login')->with('message', 'Your account has been successfully deleted.');
  }

  /**
   * Remove the specified resource from storage (disabled now, do not call directly)
   */
  public function destroy($id)
  {
    // You can keep this method for direct deletion if you want,
    // but it’s safer to delete only via verifyDelete after password check.
    abort(403, 'Direct delete not allowed. Please confirm your password.');
  }
}
