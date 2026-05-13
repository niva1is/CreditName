{{-- resources/views/loans/overview.blade.php --}}
@extends('layouts.app')

@section('title', 'Обзор кредитов ЮЛ')
@section('page_title', 'Обзор кредитов ЮЛ')

@section('content')
<style>
    /* Существующие стили из Новый.html */
    :root {
        --primary: #3261EC;
        --primary-hover: #1E4BD2;
        --text-primary: #2f3441;
        --text-secondary: #606981;
        --text-muted: #b6c1dd;
        --bg-primary: #ffffff;
        --bg-secondary: #f3f7fa;
        --bg-tertiary: #E9ECEF;
        --border-primary: #d4d7df;
        --border-secondary: #EDF6FF;
        --accent-blue: #0a2896;
        --accent-light-blue: #EDF6FF;
        --font-family: 'VTB Group UI', sans-serif;
        --radius-sm: 8px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --radius-xl: 48px;
    }

    body {
        font-family: var(--font-family);
        background: #ffffff;
        color: var(--text-primary);
        line-height: 1.15;
    }

    .page-header__title {
        font-family: var(--font-family);
        color: var(--text-primary);
        font-size: 34px;
        font-weight: 400;
        line-height: 34px;
        letter-spacing: -2px;
    }

    @media screen and (min-width: 1024px) {
        .page-header__title {
            font-size: 50px;
            line-height: 50px;
            letter-spacing: -3px;
        }
    }

    .page-header__subtitle {
        font-size: 14px;
        font-weight: 400;
        color: var(--text-secondary);
        margin-top: 8px;
    }

    @media screen and (min-width: 1024px) {
        .page-header__subtitle {
            font-size: 18px;
        }
    }

    .card-container {
        background-color: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: 0px 2px 20px 0px rgba(21, 93, 241, 0.05);
        border: 1px solid var(--border-primary);
    }

    @media screen and (min-width: 1024px) {
        .card-container {
            border-radius: var(--radius-xl);
            padding: 40px;
        }
    }

    .kpi-tile {
        padding: 20px;
        border-radius: var(--radius-md);
        background: var(--bg-secondary);
        border: 1px solid var(--border-primary);
    }

    .kpi-tile-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .kpi-tile-value {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .kpi-tile-desc {
        font-size: 11px;
        color: var(--text-secondary);
    }

    .grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    /* Стили калькулятора из Новый.html */
    .calculator-container {
        background-color: var(--bg-primary);
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        user-select: none;
        align-items: flex-start;
        width: inherit;
        box-shadow: 0px 2px 20px 0px rgba(21, 93, 241, 0.05);
        border: 1px solid var(--border-primary);
        overflow: hidden;
    }

    @media (min-width: 1024px) {
        .calculator-container {
            border-radius: 48px;
            flex-direction: row;
            padding: 48px;
        }
    }

    .calculator-left {
        flex: 1;
        padding: 24px;
        width: 100%;
    }

    @media (min-width: 1024px) {
        .calculator-left {
            padding: 0 48px 0 0;
        }
    }

    .calculator-right {
        background: var(--bg-secondary);
        padding: 24px;
        border-radius: 16px;
        width: 100%;
    }

    @media (min-width: 1024px) {
        .calculator-right {
            width: 400px;
            padding: 32px;
        }
    }

    .calculator-title {
        font-size: 22px;
        font-weight: 400;
        color: var(--text-primary);
        margin-bottom: 24px;
        letter-spacing: -1px;
    }

    @media (min-width: 1024px) {
        .calculator-title {
            font-size: 32px;
            letter-spacing: -1.5px;
        }
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-label {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 8px;
        font-weight: 500;
    }

    .input-value {
        color: var(--text-primary);
        font-weight: 500;
    }

    .slider-container {
        position: relative;
        width: 100%;
    }

    .slider {
        width: 100%;
        height: 4px;
        background: var(--border-primary);
        border-radius: 2px;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .slider-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .result-block {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        border: 1px solid var(--border-primary);
    }

    .result-label {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 4px;
        font-weight: 500;
    }

    .result-value {
        font-size: 28px;
        font-weight: 600;
        color: var(--text-primary);
        font-family: monospace;
    }

    @media (min-width: 1024px) {
        .result-value {
            font-size: 36px;
        }
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: var(--primary);
        color: #ffffff;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 400;
        font-size: 14px;
        line-height: 120%;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        width: 100%;
        margin-top: 16px;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    @media (min-width: 1024px) {
        .btn-primary {
            padding: 16px 24px;
            font-size: 18px;
        }
    }

    .divider {
        height: 1px;
        background: var(--border-secondary);
        margin: 24px 0;
    }

    @media (min-width: 1024px) {
        .divider {
            margin: 32px 0 40px;
        }
    }

    .chip {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        background: var(--accent-light-blue);
        color: var(--accent-blue);
        border-radius: 100px;
        font-size: 14px;
        font-weight: 500;
    }

    .chips-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .loan-chip {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        padding: 8px 16px;
        background: #EDF6FF;
        color: var(--accent-blue);
        border-radius: 100px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .loan-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--border-primary);
        transition: box-shadow 0.2s;
    }

    .loan-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .loan-card-header {
        font-size: 18px;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 20px;
    }

    .loan-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .loan-stat-label {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .loan-stat-value {
        font-size: 15px;
        font-weight: 500;
    }

    .grid-1-2-3 {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .text-mono {
        font-family: monospace;
        font-weight: 500;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .table-wrapper table {
        width: 100%;
        border-collapse: collapse;
        font-family: var(--font-family);
    }

    .table-wrapper th {
        text-align: left;
        padding: 0 0 16px;
        font-size: 11px;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: -0.3px;
        border-bottom: 1px solid var(--border-secondary);
    }

    .table-wrapper td {
        padding: 16px 0;
        font-size: 14px;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-secondary);
    }

    .table-wrapper tr:last-child td {
        border-bottom: none;
    }
</style>

<div class="page-header">
    <div class="page-header__title">Кредитный портфель юридических лиц</div>
    <div class="page-header__subtitle">
        Динамика и структура выдач по видам кредитов и клиентам ЮЛ.
    </div>
</div>

<!-- Кредитный калькулятор из Новый.html -->
<section class="calculator-container" style="margin-top: 32px;">
    <div class="calculator-left">
        <div class="calculator-title">Калькулятор кредита для бизнеса</div>
        
        <div class="input-group">
            <div class="input-label">
                <span>Сумма кредита</span>
                <span class="input-value" id="amountValue">5 000 000 ₽</span>
            </div>
            <div class="slider-container">
                <input type="range" class="slider" id="amountSlider" min="100000" max="50000000" value="5000000" step="100000" oninput="updateCalculator()">
            </div>
            <div class="slider-labels">
                <span>100 000 ₽</span>
                <span>50 000 000 ₽</span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-label">
                <span>Срок кредита</span>
                <span class="input-value" id="termValue">24 месяца</span>
            </div>
            <div class="slider-container">
                <input type="range" class="slider" id="termSlider" min="6" max="84" value="24" step="1" oninput="updateCalculator()">
            </div>
            <div class="slider-labels">
                <span>6 мес</span>
                <span>84 мес</span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-label">
                <span>Процентная ставка</span>
                <span class="input-value" id="rateValue">18%</span>
            </div>
            <div class="slider-container">
                <input type="range" class="slider" id="rateSlider" min="5" max="30" value="18" step="0.5" oninput="updateCalculator()">
            </div>
            <div class="slider-labels">
                <span>5%</span>
                <span>30%</span>
            </div>
        </div>

        <div class="chips-row">
            <span class="chip">Без залога</span>
            <span class="chip">Решение от 2 минут</span>
            <span class="chip">Для ИП и ООО</span>
        </div>
    </div>

    <div class="calculator-right" id="calculatorResult">
        <div class="result-block">
            <div class="result-label">Ежемесячный платёж</div>
            <div class="result-value" id="monthlyPayment">183 338 ₽</div>
        </div>
        
        <div class="result-block">
            <div class="result-label">Общая сумма выплат</div>
            <div class="result-value" id="totalPayment" style="font-size: 22px;">4 400 112 ₽</div>
        </div>

        <div class="result-block">
            <div class="result-label">Переплата по кредиту</div>
            <div class="result-value" id="overpayment" style="font-size: 22px; color: var(--text-secondary);">-599 888 ₽</div>
        </div>

        <a href="/r/cl/" class="btn-primary">Оформить кредит</a>
    </div>
</section>

<!-- KPI Стрип -->
<section class="card-container" style="margin-top: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Общая информация</div>
            <div style="font-size: 22px; font-weight: 500; margin-top: 4px; color: var(--text-primary);">Портфель кредитов ЮЛ</div>
        </div>
        <span class="loan-chip">Агрегировано по всем видам</span>
    </div>

    <div class="divider"></div>

    <div class="grid-3">
        <div class="kpi-tile">
            <div class="kpi-tile-label">Общий объём портфеля</div>
            <div class="kpi-tile-value">{{ number_format($totalPortfolio, 0, ',', ' ') }} ₽</div>
            <div class="kpi-tile-desc">Сумма всех зарегистрированных выдач</div>
        </div>
        <div class="kpi-tile">
            <div class="kpi-tile-label">Количество выдач</div>
            <div class="kpi-tile-value">{{ $totalLoans }}</div>
            <div class="kpi-tile-desc">Число кредитных операций</div>
        </div>
        <div class="kpi-tile">
            <div class="kpi-tile-label">Клиентов в портфеле</div>
            <div class="kpi-tile-value">{{ $activeClients }}</div>
            <div class="kpi-tile-desc">ЮЛ с зарегистрированными кредитами</div>
        </div>
    </div>
</section>

<!-- Структура по видам кредитов -->
<section class="card-container" style="margin-top: 24px;">
    <div>
        <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Структура портфеля</div>
        <div style="font-size: 22px; font-weight: 500; margin-top: 4px;">Распределение по видам кредитов</div>
    </div>

    <div class="divider"></div>

    <div class="grid-1-2-3">
        @foreach($productStats as $product)
        <div class="loan-card">
            <div class="loan-card-header">{{ $product->name }}</div>
            <div class="loan-stat">
                <span class="loan-stat-label">Количество выдач</span>
                <span class="loan-stat-value">{{ $product->loans_count }}</span>
            </div>
            <div class="loan-stat">
                <span class="loan-stat-label">Объём портфеля</span>
                <span class="loan-stat-value text-mono">{{ number_format($product->total_amount, 0, ',', ' ') }} ₽</span>
            </div>
            <div class="loan-stat">
                <span class="loan-stat-label">Средняя ставка</span>
                <span class="chip" style="font-size: 12px; padding: 4px 12px;">{{ $product->base_rate }}%</span>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- График по месяцам -->
<section class="card-container" style="margin-top: 24px;">
    <div>
        <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Динамика работы кредитного отдела</div>
        <div style="font-size: 22px; font-weight: 500; margin-top: 4px;">Выдачи по месяцам (млн ₽)</div>
    </div>

    <div style="position: relative; height: 300px; margin-top: 24px;">
        <canvas id="loansChart"></canvas>
    </div>
</section>

<script>
    // Калькулятор кредита
    function updateCalculator() {
        const amount = parseInt(document.getElementById('amountSlider').value);
        const term = parseInt(document.getElementById('termSlider').value);
        const rate = parseFloat(document.getElementById('rateSlider').value);
        
        // Форматирование отображаемых значений
        document.getElementById('amountValue').textContent = new Intl.NumberFormat('ru-RU').format(amount) + ' ₽';
        document.getElementById('termValue').textContent = term + ' ' + getTermDeclension(term);
        document.getElementById('rateValue').textContent = rate + '%';
        
        // Расчёт платежа
        const monthlyRate = rate / 100 / 12;
        const monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, term) / (Math.pow(1 + monthlyRate, term) - 1);
        const totalPayment = monthlyPayment * term;
        const overpayment = totalPayment - amount;
        
        // Отображение результатов
        document.getElementById('monthlyPayment').textContent = new Intl.NumberFormat('ru-RU').format(Math.round(monthlyPayment)) + ' ₽';
        document.getElementById('totalPayment').textContent = new Intl.NumberFormat('ru-RU').format(Math.round(totalPayment)) + ' ₽';
        document.getElementById('overpayment').textContent = new Intl.NumberFormat('ru-RU').format(Math.round(overpayment)) + ' ₽';
    }
    
    function getTermDeclension(months) {
        if (months % 12 === 0) {
            const years = months / 12;
            if (years === 1) return 'год';
            if (years >= 2 && years <= 4) return 'года';
            return 'лет';
        }
        if (months === 1) return 'месяц';
        if (months >= 2 && months <= 4) return 'месяца';
        return 'месяцев';
    }

    // График
    const monthlyData = @json($monthlyData);
    const labels = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];
    
    const data = [];
    for (let i = 1; i <= 12; i++) {
        data.push((monthlyData[i] || 0) / 1000000);
    }

    const ctx = document.getElementById('loansChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                borderColor: '#3261EC',
                backgroundColor: 'rgba(50, 97, 236, 0.12)',
                borderWidth: 3,
                tension: 0.35,
                pointRadius: 0,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    grid: { color: '#E5E7EB' },
                    ticks: { 
                        color: '#6B7280', 
                        font: { size: 11 },
                        callback: function(value) { return value + ' млн'; }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#6B7280', font: { size: 11 } }
                }
            }
        }
    });

    // Инициализация калькулятора
    updateCalculator();
</script>
@endsection