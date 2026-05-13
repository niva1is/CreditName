<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Manager\RegistrationController;
use App\Http\Controllers\ClientProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

// ==================== ГОСТЕВЫЕ МАРШРУТЫ ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
    Route::get('/registration-success', [RegisterController::class, 'success'])->name('registration.success');
});

// ==================== ВСЕ АВТОРИЗОВАННЫЕ ====================
Route::middleware(['auth'])->group(function () {

    // Дашборд
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Реестр кредитов
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');

    // Создание кредита
    Route::middleware('role:admin,credit_manager,supervisor,client')->group(function () {
        Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    });

    // Компании — просмотр
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{client}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/clients/{client}', [CompanyController::class, 'show'])->name('clients.show');

    // Компании — редактирование
    Route::middleware('role:admin,supervisor,credit_manager')->group(function () {
        Route::get('/companies/{client}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/companies/{client}', [CompanyController::class, 'update'])->name('companies.update');
        Route::post('/companies/{client}/documents', [CompanyController::class, 'uploadDocument'])->name('companies.upload-document');
        Route::delete('/documents/{document}', [CompanyController::class, 'deleteDocument'])->name('companies.delete-document');
    });

    // Управление заявками на регистрацию
    Route::middleware('role:admin,credit_manager,supervisor')->prefix('manager')->name('manager.')->group(function () {
        Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
        Route::post('/registrations/{registration}/approve', [RegistrationController::class, 'approve'])->name('registrations.approve');
        Route::post('/registrations/{registration}/reject', [RegistrationController::class, 'reject'])->name('registrations.reject');
        Route::get('/registrations/{registration}/documents/{document}/download', [RegistrationController::class, 'downloadDocument'])->name('registrations.download-document');
    });

    // ==================== ЛИЧНЫЙ КАБИНЕТ КЛИЕНТА ====================
    Route::middleware('role:client')->prefix('client')->name('client.')->group(function () {
        // Дашборд
        Route::get('/dashboard', function () {
            $client = \App\Models\Client::where('user_id', auth()->id())->first();
            return view('client.dashboard', compact('client'));
        })->name('dashboard');
        
        // Профиль
        Route::get('/profile', [ClientProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
        
        // Смена пароля
        Route::put('/password', [ClientProfileController::class, 'updatePassword'])->name('password.update');
        
        // Документы
        Route::post('/documents', [ClientProfileController::class, 'uploadDocuments'])->name('documents.upload');
        Route::delete('/documents/{document}', [ClientProfileController::class, 'deleteDocument'])->name('documents.delete');
        
        // Кредиты клиента
        Route::get('/loans', function () {
            $client = \App\Models\Client::where('user_id', auth()->id())->first();
            $loans = $client ? $client->loanApplications()->with('creditProduct')->orderBy('created_at', 'desc')->get() : collect();
            return view('client.loans', compact('loans'));
        })->name('loans');
    });

    // Выход
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});