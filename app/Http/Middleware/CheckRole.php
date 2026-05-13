<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Если роли не указаны - пропускаем всех
        if (empty($roles)) {
            return $next($request);
        }

        // Проверяем через модель User
        if (auth()->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        abort(403, 'У вас недостаточно прав для доступа к этой странице.');
    }
}