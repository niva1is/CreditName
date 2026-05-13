<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка отправлена</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F5ECC, #020617);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 24px;
            padding: 48px;
            text-align: center;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .icon { font-size: 64px; margin-bottom: 24px; }
        h1 { font-size: 24px; margin-bottom: 12px; }
        p { color: #6B7280; line-height: 1.6; margin-bottom: 24px; }
        .request-id {
            background: #F3F4F6;
            padding: 12px 20px;
            border-radius: 12px;
            display: inline-block;
            font-weight: 600;
            color: #0F5ECC;
        }
        .btn {
            display: inline-block;
            margin-top: 24px;
            padding: 14px 32px;
            background: linear-gradient(135deg, #1D4ED8, #0F5ECC);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">✅</div>
        <h1>Заявка успешно отправлена!</h1>
        <p>Ваша заявка на регистрацию компании принята и будет рассмотрена менеджером банка в течение 1-2 рабочих дней.</p>
        
        @if(isset($requestId))
            <div class="request-id">Номер заявки: #{{ $requestId }}</div>
        @endif
        
        <p style="margin-top: 20px; font-size: 14px;">
            После одобрения вы получите уведомление на указанный email.
        </p>
        
        <a href="{{ route('login') }}" class="btn">Перейти на страницу входа</a>
    </div>
</body>
</html>