<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Показать форму входа
     */
    public function showLoginForm()
    {
        // Если есть админ в базе, показываем форму
        if (User::count() === 0) {
            // Создаём админа автоматически, если пользователей нет
            User::create([
                'name' => 'Администратор',
                'email' => 'admin@alfabank.ru',
                'password' => bcrypt('password123'),
                'role' => 'admin',
            ]);
        }
        
        return view('auth.login');
    }

    /**
     * Обработать вход в систему
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->hasRole('client')) {
                return redirect()->route('client.profile'); // ← Изменили на profile
            }
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Неверный email или пароль']);
    }
    
    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Вы успешно вышли из системы.');
    }
}