<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Symfony\Component\HttpFoundation\Response;
// use App\Models\User;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->to('login');
        }
        $roles = auth()->user()->roles;
        // dd($roles);
        $adminRole = $roles->where('name', 'admin')->first();
        // dd($adminRole);
        if ($adminRole) {
            return $next($request);
        }
        return redirect()->to('login');
    }
}
