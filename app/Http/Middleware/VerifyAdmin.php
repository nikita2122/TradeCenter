<?php
/**
 * Created by PhpStorm.
 * User: R
 * Date: 10/19/2022
 * Time: 10:02 PM
 */

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VerifyAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !Auth::user() || Auth::user()->approve != User::ADMIN)
            return redirect(RouteServiceProvider::HOME);
        return $next($request);
    }
}