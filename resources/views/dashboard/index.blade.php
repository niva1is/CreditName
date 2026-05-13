{{-- resources/views/loans/overview.blade.php --}}
@extends('layouts.app')

@section('title', 'Обзор кредитов ЮЛ')
@section('page_title', 'Обзор кредитов ЮЛ')

@section('content')
<style>
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
        --success: #22C55E;
    }

    body {
        font-family: var(--font-family);
        background: #ffffff;
        color: var(--text-primary);
        line-height: 1.15;
        -webkit-font-smoothing: antialiased;
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

    /* Калькулятор в стиле ВТБ */
    .calculator-vtb {
        background: var(--bg-primary);
        border-radius: 24px;
        box-shadow: 0px 2px 20px 0px rgba(21, 93, 241, 0.05);
        border: 1px solid var(--border-primary);
        overflow: hidden;
    }

    @media (min-width: 1024px) {
        .calculator-vtb {
            border-radius: 48px;
        }
    }

    .calculator-vtb__inner {
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 1024px) {
        .calculator-vtb__inner {
            flex-direction: row;
            padding: 48px;
            gap: 48px;
        }
    }

    .calculator-vtb__left {
        flex: 1;
        padding: 24px;
    }

    @media (min-width: 1024px) {
        .calculator-vtb__left {
            padding: 0;
        }
    }

    .calculator-vtb__right {
        background: var(--bg-secondary);
        padding: 24px;
        border-radius: 16px;
    }

    @media (min-width: 1024px) {
        .calculator-vtb__right {
            width: 380px;
            padding: 32px;
            border-radius: 24px;
        }
    }

    .calc-section {
        margin-bottom: 32px;
    }

    .calc-section__title {
        font-size: 22px;
        font-weight: 400;
        color: var(--text-primary);
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    @media (min-width: 1024px) {
        .calc-section__title {
            font-size: 32px;
            letter-spacing: -1.5px;
        }
    }

    .calc-amount-display {
        font-size: 32px;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 20px;
        font-family: monospace;
    }

    @media (min-width: 1024px) {
        .calc-amount-display {
            font-size: 44px;
        }
    }

    .calc-amount-range {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 12px;
    }

    /* Обновлённые стили ползунка */
    .calc-slider {
        width: 100%;
        height: 6px;
        -webkit-appearance: none;
        appearance: none;
        background: transparent;
        border-radius: 3px;
        outline: none;
        margin: 0;
        cursor: pointer;
        position: relative;
        z-index: 2;
    }

    .calc-slider::-webkit-slider-runnable-track {
        width: 100%;
        height: 4px;
        border-radius: 2px;
        background: transparent;
    }

    .calc-slider::-moz-range-track {
        width: 100%;
        height: 4px;
        border-radius: 2px;
        background: transparent;
        border: none;
    }

    .calc-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        cursor: pointer;
        border: 3px solid var(--primary);
        box-shadow: 0 2px 8px rgba(50, 97, 236, 0.3);
        margin-top: -10px;
        position: relative;
        z-index: 2;
    }

    .calc-slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        cursor: pointer;
        border: 3px solid var(--primary);
        box-shadow: 0 2px 8px rgba(50, 97, 236, 0.3);
        position: relative;
        z-index: 2;
    }

    /* Контейнер для прогресс-бара */
    /* Обновлённые стили ползунка */
    .slider-wrapper {
        position: relative;
        width: 100%;
        margin: 8px 0;
        height: 24px;
        display: flex;
        align-items: center;
    }

    /* Серый фон трека */
    .slider-track-bg {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        height: 4px;
        background: var(--border-primary);
        border-radius: 2px;
        pointer-events: none;
    }

    /* Синий прогресс слева от ползунка */
    .slider-progress {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 4px;
        background: var(--primary);
        border-radius: 2px;
        pointer-events: none;
        z-index: 1;
        transition: width 0.1s ease;
    }

    .calc-slider {
        width: 100%;
        height: 24px;
        -webkit-appearance: none;
        appearance: none;
        background: transparent;
        outline: none;
        margin: 0;
        cursor: pointer;
        position: relative;
        z-index: 2;
    }

    .calc-slider::-webkit-slider-runnable-track {
        width: 100%;
        height: 4px;
        background: transparent;
        border: none;
    }

    .calc-slider::-moz-range-track {
        width: 100%;
        height: 4px;
        background: transparent;
        border: none;
    }

    .calc-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        cursor: pointer;
        border: 3px solid var(--primary);
        box-shadow: 0 2px 8px rgba(50, 97, 236, 0.3);
        margin-top: -10px;
    }

    .calc-slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        cursor: pointer;
        border: 3px solid var(--primary);
        box-shadow: 0 2px 8px rgba(50, 97, 236, 0.3);
    }

    /* Выбор вида кредита */
    .credit-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 8px;
        margin-top: 12px;
    }

    .credit-type-option {
        position: relative;
        cursor: pointer;
    }

    .credit-type-option input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .credit-type-card {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 16px 12px;
        border: 2px solid var(--border-primary);
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-secondary);
        transition: all 0.2s;
        background: white;
        min-height: 60px;
    }

    .credit-type-option input:checked + .credit-type-card {
        border-color: var(--primary);
        background: var(--accent-light-blue);
        color: var(--primary);
    }

    .credit-type-card:hover {
        border-color: var(--primary);
        background: #F8FAFF;
    }

    @media (min-width: 1024px) {
        .credit-type-card {
            font-size: 16px;
            padding: 20px 16px;
        }
    }

    /* Правая панель */
    .calc-result {
        margin-bottom: 0;
    }

    .calc-result__label {
        font-size: 18px;
        font-weight: 400;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .calc-result__payment {
        font-size: 36px;
        font-weight: 500;
        color: var(--text-primary);
        font-family: monospace;
        margin-bottom: 24px;
    }

    @media (min-width: 1024px) {
        .calc-result__payment {
            font-size: 44px;
        }
    }

    .calc-docs {
        margin-bottom: 24px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-primary);
    }

    .calc-docs__title {
        font-size: 14px;
        color: var(--text-primary);
        margin-bottom: 12px;
        font-weight: 500;
    }

    .calc-docs__item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .calc-docs__item:last-child {
        margin-bottom: 0;
    }

    .calc-docs__item::before {
        content: '—';
        color: var(--text-muted);
        flex-shrink: 0;
        margin-top: 1px;
    }

    .btn-vtb {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 16px 24px;
        font-size: 14px;
        font-weight: 400;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
        margin-bottom: 12px;
    }

    .btn-vtb:hover {
        background: var(--primary-hover);
    }

    @media (min-width: 1024px) {
        .btn-vtb {
            font-size: 18px;
            padding: 16px 32px;
        }
    }

    .btn-vtb--outline {
        background: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-vtb--outline:hover {
        background: var(--accent-light-blue);
    }

    .calc-disclaimer {
        font-size: 11px;
        color: var(--text-muted);
        line-height: 1.4;
        margin-top: 16px;
    }

    /* Стили для остальных элементов */
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
    }

    .loan-card {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--border-primary);
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

    .section-title {
        font-size: 28px;
        font-weight: 400;
        color: var(--text-primary);
        margin-bottom: 8px;
        letter-spacing: -1px;
    }

    @media (min-width: 1024px) {
        .section-title {
            font-size: 40px;
            letter-spacing: -2px;
        }
    }
</style>

<div class="page-header">
    <div class="page-header__title">Кредитный портфель юридических лиц</div>
    <div class="page-header__subtitle">
        Динамика и структура выдач по видам кредитов и клиентам ЮЛ.
    </div>
</div>

<!-- KPI Стрип -->
<section class="card-container" style="margin-top: 32px;">
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

<!-- КАЛЬКУЛЯТОР КРЕДИТА ДЛЯ ЮЛ (в конце страницы) -->
<div class="section-title" style="margin-top: 48px;">Рассчитайте кредит для бизнеса</div>

<section class="calculator-vtb" style="margin-top: 24px;">
    <div class="calculator-vtb__inner">
        <!-- Левая часть -->
        <div class="calculator-vtb__left">
            <!-- Сумма -->
            <div class="calc-section">
                <div class="calc-section__title">Какая сумма вас интересует?</div>
                <div class="calc-amount-display" id="calcAmountDisplay">5 000 000 ₽</div>
                <div class="slider-wrapper">
                    <div class="slider-track-bg"></div>
                    <div class="slider-progress" id="amountProgress" style="width: 0%;"></div>
                    <input type="range" class="calc-slider" id="calcAmountSlider" 
                        min="500000" max="50000000" value="5000000" step="100000">
                </div>
                <div class="calc-amount-range">
                    <span>500 тыс ₽</span>
                    <span>20 млн ₽</span>
                    <span>50 млн ₽</span>
                </div>
            </div>

            <!-- Срок -->
            <div class="calc-section">
                <div class="calc-section__title">На какой срок хотите взять кредит?</div>
                <div class="calc-term-display" id="calcTermDisplay">2 года</div>
                <div class="slider-wrapper">
                    <div class="slider-track-bg"></div>
                    <div class="slider-progress" id="termProgress" style="width: 0%;"></div>
                    <input type="range" class="calc-slider" id="calcTermSlider" 
                        min="6" max="84" value="24" step="1">
                </div>
                <div class="calc-amount-range">
                    <span>6 месяцев</span>
                    <span>3 года 9 месяцев</span>
                    <span>7 лет</span>
                </div>
            </div>

            <!-- Выбор вида кредита -->
            <div class="calc-section">
                <div class="calc-section__title">Вид кредита</div>
                <div class="credit-type-grid">
                    <label class="credit-type-option">
                        <input type="radio" name="creditType" value="оборотный" checked onchange="updateVtbCalculator()">
                        <div class="credit-type-card">Оборотный</div>
                    </label>
                    <label class="credit-type-option">
                        <input type="radio" name="creditType" value="инвестиционный" onchange="updateVtbCalculator()">
                        <div class="credit-type-card">Инвестиционный</div>
                    </label>
                    <label class="credit-type-option">
                        <input type="radio" name="creditType" value="овердрафт" onchange="updateVtbCalculator()">
                        <div class="credit-type-card">Овердрафт</div>
                    </label>
                    <label class="credit-type-option">
                        <input type="radio" name="creditType" value="на госзаказ" onchange="updateVtbCalculator()">
                        <div class="credit-type-card">На госзаказ</div>
                    </label>
                    <label class="credit-type-option">
                        <input type="radio" name="creditType" value="рефинансирование" onchange="updateVtbCalculator()">
                        <div class="credit-type-card">Рефинансирование</div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Правая часть -->
        <div class="calculator-vtb__right">
            <div class="calc-result">
                <div class="calc-result__label">Ежемесячный платеж</div>
                <div class="calc-result__payment" id="calcMonthlyPayment">183 338 ₽</div>
            </div>

            <div class="calc-docs">
                <div class="calc-docs__title">Вам понадобится</div>
                <div class="calc-docs__item">Учредительные документы (Устав, ИНН, ОГРН)</div>
                <div class="calc-docs__item">Паспорт руководителя и главного бухгалтера</div>
                <div class="calc-docs__item">Бухгалтерская отчётность за последние 12 месяцев</div>
                <div class="calc-docs__item" id="calcExtraDoc">Справка об оборотах по счетам</div>
            </div>

            <a href="#" class="btn-vtb">Оформить заявку</a>
            <a href="#" class="btn-vtb btn-vtb--outline">График платежей</a>

            <div class="calc-disclaimer">
                Предварительный расчёт. Итоговая ставка и условия определяются после анализа финансового состояния компании. Не является публичной офертой.
            </div>
        </div>
    </div>
</section>

<script>
    // Функция обновления синего прогресса слева от ползунка
    function updateSliderProgress(slider, progressId) {
        const min = parseFloat(slider.min);
        const max = parseFloat(slider.max);
        const value = parseFloat(slider.value);
        const percentage = ((value - min) / (max - min)) * 100;
        
        // Убеждаемся что процент в пределах 0-100
        const clampedPercentage = Math.max(0, Math.min(100, percentage));
        
        const progressBar = document.getElementById(progressId);
        if (progressBar) {
            progressBar.style.width = clampedPercentage + '%';
        }
    }

    // Добавьте вызов при инициализации:
    document.addEventListener('DOMContentLoaded', function() {
        updateVtbCalculator();
        
        // Добавляем слушатели для каждого ползунка
        const amountSlider = document.getElementById('calcAmountSlider');
        const termSlider = document.getElementById('calcTermSlider');
        
        if (amountSlider) {
            amountSlider.addEventListener('input', function() {
                updateVtbCalculator();
                updateSliderProgress(this, 'amountProgress');
            });
        }
        
        if (termSlider) {
            termSlider.addEventListener('input', function() {
                updateVtbCalculator();
                updateSliderProgress(this, 'termProgress');
            });
        }
    });
    // Форматирование срока
    function formatTerm(months) {
        const years = Math.floor(months / 12);
        const remainingMonths = months % 12;
        
        let result = '';
        if (years > 0) {
            if (years === 1) result += '1 год';
            else if (years >= 2 && years <= 4) result += years + ' года';
            else result += years + ' лет';
        }
        if (remainingMonths > 0) {
            if (result) result += ' ';
            if (remainingMonths === 1) result += '1 месяц';
            else if (remainingMonths >= 2 && remainingMonths <= 4) result += remainingMonths + ' месяца';
            else result += remainingMonths + ' месяцев';
        }
        return result || '0 месяцев';
    }

    // Расчёт калькулятора
    function updateVtbCalculator() {
        const amount = parseInt(document.getElementById('calcAmountSlider').value);
        const term = parseInt(document.getElementById('calcTermSlider').value);
        
        // Получаем выбранный вид кредита
        const creditTypeRadio = document.querySelector('input[name="creditType"]:checked');
        const creditType = creditTypeRadio ? creditTypeRadio.value : 'оборотный';
        
        // Обновление отображения
        document.getElementById('calcAmountDisplay').textContent = 
            new Intl.NumberFormat('ru-RU').format(amount) + ' ₽';
        document.getElementById('calcTermDisplay').textContent = formatTerm(term);
        
        // Базовая ставка зависит от вида кредита
        let rate = 18;
        switch(creditType) {
            case 'оборотный': rate = 16; break;
            case 'инвестиционный': rate = 14; break;
            case 'овердрафт': rate = 20; break;
            case 'на госзаказ': rate = 12; break;
            case 'рефинансирование': rate = 15; break;
        }
        
        // Корректировка ставки от суммы и срока
        if (amount > 20000000) rate += 1;
        if (term > 60) rate += 0.5;
        
        // Расчёт платежа
        const monthlyRate = rate / 100 / 12;
        let monthlyPayment;
        
        if (monthlyRate === 0) {
            monthlyPayment = amount / term;
        } else {
            monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, term) / 
                           (Math.pow(1 + monthlyRate, term) - 1);
        }
        
        // Отображение
        document.getElementById('calcMonthlyPayment').textContent = 
            new Intl.NumberFormat('ru-RU').format(Math.round(monthlyPayment)) + ' ₽';
        
        // Дополнительные документы в зависимости от суммы
        const extraDoc = document.getElementById('calcExtraDoc');
        if (amount > 10000000) {
            extraDoc.style.display = 'flex';
        } else {
            extraDoc.style.display = 'none';
        }
        
        // Обновление прогресс-баров
        updateSliderProgress(document.getElementById('calcAmountSlider'), 'amountProgress');
        updateSliderProgress(document.getElementById('calcTermSlider'), 'termProgress');
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

    // Инициализация
    updateVtbCalculator();
</script>
@endsection