<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Session;

class MakeAdminController extends Controller
{
    public function assignRole(Request $request)
    {
        $currentUid = Session::get('uid');

        // Only allow specific user to assign roles
        if ($currentUid !== 'F7Nl2rxMsvXiBMhU0KR4BkK4oKq2') {
            return redirect('/home')->with('error', 'Unauthorized');
        }

        $targetUid = $request->input('uid');
        $role = $request->input('role'); // admin or organizer

        if (!$targetUid || !$role) {
            return redirect('/home')->with('error', 'Missing UID or role');
        }

        $auth = app('firebase.auth');

        // Set custom claim based on role
        $claims = [];

        if ($role === 'admin') {
            $claims = ['admin' => true];
        } elseif ($role === 'organizer') {
            $claims = ['organizer' => true];
        } else {
            return redirect('/home')->with('error', 'Invalid role selected');
        }

        $auth->setCustomUserClaims($targetUid, $claims);

        return redirect('/home')->with('success', "User $targetUid assigned role: $role");
    }

    public function showForm()
    {
        return view('assign-role');
    }
}
