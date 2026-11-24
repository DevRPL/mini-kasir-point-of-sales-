<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Pemakaian: EnsureRole::class.':admin'  atau  ':admin,kasir' */
class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $u = $request->user();
        if (!$u || !$u->is_active || !in_array($u->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses untuk fitur ini.');
        }
        return $next($request);
    }
}
