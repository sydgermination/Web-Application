<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uid = Session::get('uid');

        // Your developer/admin UID
        $adminUid = 'F7Nl2rxMsvXiBMhU0KR4BkK4oKq2';

        if ($uid !== null) {
            // Check if the current UID is the hardcoded admin
            if ($uid === $adminUid) {
                Session::put('is_admin', true); // Optional: allow Blade to check this
                return $next($request);
            }

            // Fallback to Firebase claims for additional admins (optional)
            $user = app('firebase.auth')->getUser($uid);
            $isAdmin = $user->customClaims['admin'] ?? false;

            Session::put('is_admin', $isAdmin);

            if ($isAdmin) {
                return $next($request);
            }

            return redirect('/home')->with('error', 'You are not admin');
        }

        return redirect('/home')->with('message', 'You are not admin');
    }
}
