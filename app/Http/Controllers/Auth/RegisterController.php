<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Показать форму регистрации
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Обработать заявку на регистрацию
     */
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Данные компании
            'company_full_name' => 'required|string|max:255',
            'company_short_name' => 'required|string|max:255',
            'inn' => 'required|string|size:12|unique:registration_requests',
            'ogrn' => 'required|string|size:13|unique:registration_requests',
            'ownership_form' => 'required|in:ООО,АО,ПАО,ЗАО',
            'legal_address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            
            // Данные для входа
            'email' => 'required|email|unique:registration_requests|unique:users',
            'password' => 'required|min:8|confirmed',
            
            // 👇 Валидация чекбоксов
            'agree_personal' => 'required|accepted',
            'agree_terms' => 'required|accepted',
            'agree_privacy' => 'required|accepted',
            'agree_newsletters' => 'nullable|boolean',
        ], [
            'inn.size' => 'ИНН должен содержать 12 цифр',
            'ogrn.size' => 'ОГРН должен содержать 13 цифр',
            'email.unique' => 'Этот email уже используется',
            'agree_personal.required' => 'Необходимо дать согласие на обработку персональных данных',
            'agree_personal.accepted' => 'Необходимо дать согласие на обработку персональных данных',
            'agree_terms.required' => 'Необходимо принять условия обслуживания',
            'agree_terms.accepted' => 'Необходимо принять условия обслуживания',
            'agree_privacy.required' => 'Необходимо ознакомиться с политикой конфиденциальности',
            'agree_privacy.accepted' => 'Необходимо ознакомиться с политикой конфиденциальности',
        ]);

        // Создаём заявку
        $registrationRequest = RegistrationRequest::create([
            'company_full_name' => $validated['company_full_name'],
            'company_short_name' => $validated['company_short_name'],
            'inn' => $validated['inn'],
            'ogrn' => $validated['ogrn'],
            'ownership_form' => $validated['ownership_form'],
            'legal_address' => $validated['legal_address'],
            'phone' => $validated['phone'],
            'contact_person' => $validated['contact_person'],
            'contact_email' => $validated['contact_email'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'status' => 'pending',
        ]);

        return redirect()->route('registration.success')
            ->with('request_id', $registrationRequest->id);
    }

    /**
     * Страница успешной отправки заявки
     */
    public function success()
    {
        $requestId = session('request_id');
        return view('auth.registration_success', compact('requestId'));
    }
}