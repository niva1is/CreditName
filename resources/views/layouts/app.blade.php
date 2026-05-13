<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Альфа-Бизнес | @yield('title', 'Кредитный отдел')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --vtb-blue: #0A2896;
            --vtb-bright-blue: #3261EC;
            --vtb-hover-blue: #0084FF;
            --vtb-bg: #F3F7FA;
            --vtb-white: #FFFFFF;
            --vtb-text: #2F3441;
            --vtb-text-secondary: #606981;
            --vtb-border: #D4D7DF;
            --vtb-light-bg: #EDF6FF;
            --vtb-green: #16A34A;
            --vtb-red: #DC2626;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --shadow-card: 0px 2px 20px 0px rgba(21, 93, 241, 0.05);
            --shadow-hover: 0px 4px 24px 0px rgba(21, 93, 241, 0.12);
            --transition: all 0.2s ease;
            --sidebar-width: 240px;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-family);
            background: var(--vtb-bg);
            color: var(--vtb-text);
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.3px;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ====== SIDEBAR ====== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--vtb-white);
            border-right: 1px solid var(--vtb-border);
            padding: 24px 12px;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            box-shadow: none;
        }

        .sidebar__logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px;
            margin-bottom: 32px;
        }

        .sidebar__logo-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--vtb-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
        }

        .sidebar__logo-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--vtb-text);
            line-height: 1.2;
        }

        .sidebar__logo-subtitle {
            font-size: 11px;
            color: var(--vtb-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-section { margin-bottom: 24px; }

        .nav-section__title {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--vtb-text-secondary);
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .nav {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--vtb-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
            letter-spacing: -0.3px;
        }

        .nav-link__icon { font-size: 18px; width: 24px; text-align: center; }
        .nav-link__label { flex: 1; }

        .nav-link:hover {
            background: var(--vtb-light-bg);
            color: var(--vtb-blue);
        }

        .nav-link.active {
            background: var(--vtb-light-bg);
            color: var(--vtb-bright-blue);
            font-weight: 600;
        }

        .nav-link__badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 999px;
            background: #FEE2E2;
            color: #DC2626;
            font-weight: 600;
        }

        .sidebar__footer {
            margin-top: auto;
            padding: 16px 12px 0;
            border-top: 1px solid var(--vtb-border);
            font-size: 12px;
            color: var(--vtb-text-secondary);
            line-height: 1.5;
        }

        /* ====== TOPBAR ====== */
        .topbar {
            height: 60px;
            background: var(--vtb-white);
            border-bottom: 1px solid var(--vtb-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar__title {
            font-size: 18px;
            font-weight: 600;
            color: var(--vtb-text);
            letter-spacing: -0.5px;
        }

        .topbar__right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar__tag {
            font-size: 11px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--vtb-light-bg);
            color: var(--vtb-bright-blue);
            font-weight: 500;
        }

        .topbar__user {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        .topbar__user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: var(--vtb-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 15px;
        }

        .topbar__user-name {
            font-size: 14px;
            font-weight: 500;
        }

        .topbar__user-role {
            font-size: 11px;
            color: var(--vtb-text-secondary);
        }

        .topbar__burger {
            display: none;
        }

        /* ====== MAIN CONTENT ====== */
        .main {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .content {
            padding: 32px 40px 40px;
            flex: 1;
        }

        /* ====== CARDS ====== */
        .card {
            background: var(--vtb-white);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-card);
            border: none;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card__header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            gap: 16px;
        }

        .card__title {
            font-size: 20px;
            font-weight: 600;
            color: var(--vtb-text);
            letter-spacing: -0.5px;
        }

        .card__subtitle {
            font-size: 14px;
            color: var(--vtb-text-secondary);
        }

        .card__meta {
            font-size: 11px;
            color: var(--vtb-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }

        .divider {
            height: 1px;
            background: var(--vtb-border);
            margin: 20px 0;
        }

        /* ====== FORM ELEMENTS ====== */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: var(--vtb-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input, select, textarea {
            padding: 12px 16px;
            border: 2px solid var(--vtb-border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: var(--font-family);
            color: var(--vtb-text);
            background: var(--vtb-white);
            transition: var(--transition);
            letter-spacing: -0.3px;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--vtb-bright-blue);
            box-shadow: 0 0 0 3px rgba(50, 97, 236, 0.1);
        }

        textarea { resize: vertical; min-height: 80px; }

        /* ====== BUTTONS ====== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
            font-family: var(--font-family);
            letter-spacing: -0.3px;
        }

        .btn--primary {
            background: var(--vtb-bright-blue);
            color: white;
        }

        .btn--primary:hover {
            background: #1E4BD2;
        }

        .btn--ghost {
            background: var(--vtb-white);
            color: var(--vtb-text);
            border: 2px solid var(--vtb-border);
        }

        .btn--ghost:hover {
            background: var(--vtb-light-bg);
            border-color: var(--vtb-bright-blue);
        }

        .btn--danger {
            background: var(--vtb-red);
            color: white;
        }

        .btn--danger:hover {
            background: #B91C1C;
        }

        .btn--sm {
            padding: 8px 16px;
            font-size: 12px;
        }

        /* ====== TABLES ====== */
        .table-wrapper {
            border-radius: var(--radius-md);
            border: 1px solid var(--vtb-border);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: #F9FAFB;
        }

        th {
            text-align: left;
            padding: 14px 16px;
            font-size: 11px;
            font-weight: 600;
            color: var(--vtb-text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1px solid var(--vtb-border);
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
            letter-spacing: -0.3px;
        }

        tbody tr:hover { background: #FAFBFC; }
        tbody tr:last-child td { border-bottom: none; }

        /* ====== TAGS & PILLS ====== */
        .loan-tag {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            background: var(--vtb-light-bg);
            color: var(--vtb-bright-blue);
        }

        .pill-soft {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--vtb-light-bg);
            color: var(--vtb-bright-blue);
            font-size: 12px;
            font-weight: 500;
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pill--success {
            background: #ECFDF5;
            color: #166534;
        }

        .status-pill--warning {
            background: #FFF7ED;
            color: #9A3412;
        }

        .status-pill--error {
            background: #FEF2F2;
            color: #991B1B;
        }

        .text-mono {
            font-family: 'SF Mono', 'Consolas', monospace;
            font-size: 13px;
            letter-spacing: -0.2px;
        }

        .text-strong { font-weight: 600; }
        .text-muted { color: var(--vtb-text-secondary); }

        /* ====== MESSAGES ====== */
        .message {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            letter-spacing: -0.3px;
        }

        .message--success {
            background: #ECFDF5;
            color: #166534;
            border: 1px solid #A7F3D0;
        }

        .message--error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* ====== KPI ====== */
        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .kpi-item {
            padding: 24px;
            border-radius: var(--radius-lg);
            background: var(--vtb-bg);
            text-align: center;
        }

        .kpi-item__value {
            font-size: 28px;
            font-weight: 700;
            color: var(--vtb-blue);
            letter-spacing: -1px;
        }

        .kpi-item__label {
            font-size: 13px;
            color: var(--vtb-text-secondary);
            margin-top: 4px;
        }

        /* ====== PAGE HEADER ====== */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header__title {
            font-size: 28px;
            font-weight: 600;
            color: var(--vtb-text);
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .page-header__subtitle {
            font-size: 15px;
            color: var(--vtb-text-secondary);
            letter-spacing: -0.3px;
        }

        /* ====== MOBILE ====== */
        @media (max-width: 1024px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .topbar__burger { display: flex; }
            .content { padding: 20px; }
            .kpi-strip { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .topbar { padding: 0 16px; }
            .topbar__title { font-size: 16px; }
            .card { padding: 16px; border-radius: var(--radius-md); }
            .card__title { font-size: 18px; }
            .page-header__title { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="layout">
        @include('partials.sidebar')
        
        <div class="main">
            @include('partials.topbar')
            
            <main class="content">
                @if(session('success'))
                    <div class="message message--success">✅ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="message message--error">⚠️ {{ session('error') }}</div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>