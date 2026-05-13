<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Альфа-Бизнес | Вход в систему</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0F5ECC, #020617);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .auth-container {
            background: white;
            padding: 40px 48px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            width: 100%;
            max-width: 460px;
        }
        .auth-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            justify-content: center;
            margin-bottom: 32px;
        }
        .logo-mark {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: radial-gradient(circle at 30% 30%, #38BDF8, #0F5ECC);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 24px;
        }
        .logo-text { display: flex; flex-direction: column; }
        .logo-title { font-size: 22px; font-weight: 600; color: #0F172A; }
        .logo-subtitle {
            font-size: 11px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .auth-title {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 28px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #6B7280;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.2s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0F5ECC;
            box-shadow: 0 0 0 3px rgba(15, 94, 204, 0.15);
        }
        .btn {
            width: 100%;
            padding: 14px 24px;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            background: linear-gradient(135deg, #1D4ED8, #0F5ECC);
            color: white;
            box-shadow: 0 10px 20px rgba(15, 94, 204, 0.25);
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(15, 94, 204, 0.35);
        }
        .error-message {
            background: #FEE2E2;
            color: #991B1B;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }
        .auth-footer p {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .auth-link {
            color: #0F5ECC;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .auth-link:hover {
            color: #1D4ED8;
            text-decoration: underline;
        }
        .test-access {
            background: #F0F9FF;
            border: 1px solid #BAE6FD;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #0369A1;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <div class="logo-mark">A</div>
            <div class="logo-text">
                <div class="logo-title">Альфа-Бизнес</div>
                <div class="logo-subtitle">Корпоративный блок</div>
            </div>
        </div>

        <h2 class="auth-title">Вход в систему</h2>

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', 'admin@alfabank.ru') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>

        <!-- 👇 НОВОЕ: Ссылка на регистрацию -->
        <div class="auth-footer">
            <p>Нет аккаунта?</p>
            <a href="{{ route('register') }}" class="auth-link">
                🏦 Зарегистрировать компанию
            </a>
        </div>
    </div>
</body>
</html>