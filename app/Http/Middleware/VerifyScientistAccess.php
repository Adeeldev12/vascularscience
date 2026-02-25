<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyScientistAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
{
    $user = auth()->user();

    if (!$user || $user->role !== 'scientist') {
        abort(403, 'Access denied.');
    }

    if (!$user->is_verified) {
        auth()->logout();
        return redirect('/scientist/login')
            ->withErrors(['email' => 'Your account is pending admin verification.']);
    }

    return $next($request);
}

}
