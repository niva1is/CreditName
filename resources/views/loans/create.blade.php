{{-- resources/views/loans/create.blade.php --}}
@extends('layouts.app')

@section('title', auth()->user()->role === 'manager' ? 'Заявки на кредит' : 'Оформление кредита')
@section('page_title', auth()->user()->role === 'manager' ? 'Заявки на кредит' : 'Оформление кредита')

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
        --danger: #DC2626;
        --success: #22C55E;
        --warning: #F59E0B;
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

    .divider {
        height: 1px;
        background: var(--border-secondary);
        margin: 24px 0;
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

    .section-subtitle {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 24px;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 500;
    }

    .chip-success {
        background: #DCFCE7;
        color: #166534;
    }

    .chip-warning {
        background: #FEF3C7;
        color: #92400E;
    }

    .chip-danger {
        background: #FEE2E2;
        color: #991B1B;
    }

    .chip-info {
        background: var(--accent-light-blue);
        color: var(--accent-blue);
    }

    /* Таблица */
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

    .table-wrapper tr:hover td {
        background: #F9FAFB;
    }

    /* Форма */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 1px solid var(--border-primary);
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-family: var(--font-family);
        color: var(--text-primary);
        background: white;
        transition: border 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(50, 97, 236, 0.1);
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .field-inline {
        position: relative;
        display: flex;
        align-items: center;
    }

    .field-inline input {
        flex: 1;
        padding-right: 40px;
    }

    .field-inline__suffix {
        position: absolute;
        right: 12px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .btn-vtb {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 14px 28px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-vtb:hover {
        background: var(--primary-hover);
    }

    .btn-vtb--outline {
        background: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-vtb--outline:hover {
        background: var(--accent-light-blue);
    }

    .btn-vtb--success {
        background: var(--success);
    }

    .btn-vtb--success:hover {
        background: #16A34A;
    }

    .btn-vtb--danger {
        background: transparent;
        color: var(--danger);
        border: 1px solid var(--danger);
    }

    .btn-vtb--danger:hover {
        background: #FEE2E2;
    }

    .btn-vtb--sm {
        padding: 8px 16px;
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    /* Загрузка файлов */
    .file-upload-area {
        border: 2px dashed var(--border-primary);
        border-radius: var(--radius-md);
        padding: 32px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--bg-secondary);
    }

    .file-upload-area:hover {
        border-color: var(--primary);
        background: var(--accent-light-blue);
    }

    .file-upload-area__icon {
        font-size: 32px;
        margin-bottom: 12px;
        color: var(--text-muted);
    }

    .file-upload-area__text {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 4px;
    }

    .file-upload-area__hint {
        font-size: 12px;
        color: var(--text-muted);
    }

    .file-list {
        margin-top: 12px;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: var(--bg-secondary);
        border-radius: var(--radius-sm);
        margin-bottom: 8px;
        font-size: 13px;
    }

    .file-item__name {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
    }

    .file-item__remove {
        color: var(--danger);
        cursor: pointer;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .file-item__remove:hover {
        background: #FEE2E2;
    }

    /* Модальное окно */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: white;
        border-radius: var(--radius-lg);
        padding: 32px;
        max-width: 700px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal__title {
        font-size: 22px;
        font-weight: 500;
        margin-bottom: 24px;
    }

    .modal__close {
        float: right;
        cursor: pointer;
        font-size: 24px;
        color: var(--text-secondary);
        background: none;
        border: none;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-secondary);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--text-secondary);
        font-size: 13px;
    }

    .detail-value {
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
</style>

@if(auth()->user()->role === 'manager')
    {{-- ==================== ИНТЕРФЕЙС СОТРУДНИКА ==================== --}}
    <div class="page-header">
        <div class="section-title">Заявки на кредит</div>
        <div class="section-subtitle">
            Управление заявками юридических лиц на получение кредита
        </div>
    </div>

    <section class="card-container">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Компания</th>
                        <th>Вид кредита</th>
                        <th>Сумма</th>
                        <th>Срок</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loanApplications as $app)
                    <tr>
                        <td style="font-weight: 500;">#{{ $app->id }}</td>
                        <td>{{ $app->client->short_name ?? '—' }}</td>
                        <td>{{ $app->creditProduct->name ?? '—' }}</td>
                        <td class="text-mono" style="font-weight: 500;">{{ number_format($app->amount, 0, ',', ' ') }} ₽</td>
                        <td>{{ $app->term_months }} мес.</td>
                        <td>
                            @if($app->status === 'pending')
                                <span class="chip chip-warning">На рассмотрении</span>
                            @elseif($app->status === 'approved')
                                <span class="chip chip-success">Одобрена</span>
                            @elseif($app->status === 'rejected')
                                <span class="chip chip-danger">Отклонена</span>
                            @else
                                <span class="chip chip-info">{{ $app->status }}</span>
                            @endif
                        </td>
                        <td>{{ $app->created_at->format('d.m.Y') }}</td>
                        <td>
                            <button onclick="openApplicationModal({{ $app->id }})" class="btn-vtb btn-vtb--outline btn-vtb--sm">
                                Просмотр
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            Нет заявок на кредит
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Модальное окно просмотра заявки -->
    <div class="modal-overlay" id="applicationModal">
        <div class="modal">
            <button class="modal__close" onclick="closeApplicationModal()">✕</button>
            <div class="modal__title" id="modalTitle">Заявка #—</div>
            
            <div id="modalContent">
                <div class="detail-row">
                    <span class="detail-label">Компания</span>
                    <span class="detail-value" id="modalCompany">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Контактное лицо</span>
                    <span class="detail-value" id="modalContact">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Вид кредита</span>
                    <span class="detail-value" id="modalProduct">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Сумма</span>
                    <span class="detail-value text-mono" id="modalAmount">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Срок</span>
                    <span class="detail-value" id="modalTerm">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Процентная ставка</span>
                    <span class="detail-value" id="modalRate">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ежемесячный платёж</span>
                    <span class="detail-value text-mono" id="modalPayment">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Дата заявки</span>
                    <span class="detail-value" id="modalDate">—</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Статус</span>
                    <span class="detail-value" id="modalStatus">—</span>
                </div>
                
                <div style="margin-top: 16px;">
                    <strong style="font-size: 13px; color: var(--text-secondary);">Документы:</strong>
                    <div id="modalDocs" style="margin-top: 8px; font-size: 13px; color: var(--text-muted);">
                        —
                    </div>
                </div>

                <div class="form-actions" id="modalActions" style="margin-top: 24px;">
                    {{-- Кнопки добавляются динамически --}}
                </div>
            </div>
        </div>
    </div>

@else
    {{-- ==================== ИНТЕРФЕЙС КЛИЕНТА ==================== --}}
    <div class="page-header">
        <div class="section-title">Оформление кредита для бизнеса</div>
        <div class="section-subtitle">
            Заполните данные для получения кредита. Решение в течение 24 часов.
        </div>
    </div>

    <div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 20px; align-items: start;">
        <!-- Форма -->
        <section class="card-container">
            <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500; margin-bottom: 4px;">Форма заявки</div>
            <div style="font-size: 22px; font-weight: 500; margin-bottom: 24px; color: var(--text-primary);">Данные кредита</div>

            <form action="{{ route('loans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-grid">
                    {{-- Выбор компании --}}
                    <div class="form-group form-group-full">
                        <label for="client_id">Компания</label>
                        <select name="client_id" id="client_id" required>
                            <option value="">— Выберите компанию —</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ auth()->user()->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->short_name }} ({{ $client->ownership_form }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Вид кредита --}}
                    <div class="form-group">
                        <label for="product_id">Вид кредита</label>
                        <select name="product_id" id="product_id" required onchange="updateRateDisplay()">
                            <option value="">— Выберите вид кредита —</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-rate="{{ $product->base_rate }}">
                                    {{ $product->name }} ({{ $product->base_rate }}%)
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Сумма --}}
                    <div class="form-group">
                        <label for="amount">Сумма кредита</label>
                        <div class="field-inline">
                            <input type="number" name="amount" id="amount" value="5000000" 
                                   placeholder="Введите сумму" required min="500000" step="0.01"
                                   oninput="calculatePayment()">
                            <span class="field-inline__suffix text-mono">₽</span>
                        </div>
                        @error('amount')
                            <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Срок --}}
                    <div class="form-group">
                        <label for="term_months">Срок кредита (месяцев)</label>
                        <select name="term_months" id="term_months" required onchange="calculatePayment()">
                            <option value="6">6 месяцев</option>
                            <option value="12">12 месяцев (1 год)</option>
                            <option value="24" selected>24 месяца (2 года)</option>
                            <option value="36">36 месяцев (3 года)</option>
                            <option value="48">48 месяцев (4 года)</option>
                            <option value="60">60 месяцев (5 лет)</option>
                            <option value="84">84 месяца (7 лет)</option>
                        </select>
                        @error('term_months')
                            <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Цель кредита --}}
                    <div class="form-group form-group-full">
                        <label for="purpose">Цель кредита</label>
                        <textarea name="purpose" id="purpose" rows="3" 
                                  placeholder="Опишите цель получения кредита (пополнение оборотных средств, закуп оборудования, расширение бизнеса и т.д.)"
                                  required></textarea>
                        @error('purpose')
                            <div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Контактное лицо --}}
                    <div class="form-group">
                        <label for="contact_person">Контактное лицо</label>
                        <input type="text" name="contact_person" id="contact_person" 
                               value="{{ auth()->user()->client->contact_person ?? '' }}"
                               placeholder="ФИО контактного лица" required>
                    </div>

                    {{-- Телефон --}}
                    <div class="form-group">
                        <label for="contact_phone">Телефон для связи</label>
                        <input type="tel" name="contact_phone" id="contact_phone" 
                               value="{{ auth()->user()->client->phone ?? '' }}"
                               placeholder="+7 (___) ___-__-__" required>
                    </div>
                </div>

                {{-- Загрузка документов --}}
                <div style="margin-top: 24px;">
                    <label style="font-size: 13px; font-weight: 500; color: var(--text-secondary); display: block; margin-bottom: 8px;">
                        Документы для заявки
                    </label>
                    
                    <div class="file-upload-area" onclick="document.getElementById('documents').click()">
                        <div class="file-upload-area__icon">📄</div>
                        <div class="file-upload-area__text">Нажмите для загрузки или перетащите файлы</div>
                        <div class="file-upload-area__hint">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG до 10 МБ</div>
                    </div>
                    <input type="file" name="documents[]" id="documents" multiple 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                           style="display: none;" onchange="updateFileList()">
                    
                    <div class="file-list" id="fileList"></div>
                </div>

                {{-- Предварительный расчёт --}}
                <div style="background: var(--bg-secondary); border-radius: var(--radius-md); padding: 20px; margin-top: 24px;">
                    <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500; margin-bottom: 4px;">Предварительный расчёт</div>
                    <div style="font-size: 28px; font-weight: 500; color: var(--text-primary); font-family: monospace;" id="calcResult">
                        183 338 ₽/мес
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                        Ежемесячный платёж при ставке <span id="rateDisplay">16%</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-vtb">
                        Отправить заявку
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-vtb btn-vtb--outline">
                        Отмена
                    </a>
                </div>
            </form>
        </section>

        <!-- Информационная панель -->
        <section class="card-container">
            <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500; margin-bottom: 4px;">Информация</div>
            <div style="font-size: 22px; font-weight: 500; margin-bottom: 24px; color: var(--text-primary);">Условия кредитования</div>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="padding: 16px; background: var(--bg-secondary); border-radius: var(--radius-md); border: 1px solid var(--border-primary);">
                    <div style="font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">📋 Необходимые документы</div>
                    <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: var(--text-secondary); line-height: 1.8;">
                        <li>Учредительные документы (Устав, ИНН, ОГРН)</li>
                        <li>Паспорт руководителя</li>
                        <li>Бухгалтерская отчётность за 12 месяцев</li>
                        <li>Справка об оборотах по счетам</li>
                    </ul>
                </div>

                <div style="padding: 16px; background: var(--accent-light-blue); border-radius: var(--radius-md); border: 1px solid var(--primary);">
                    <div style="font-size: 14px; font-weight: 500; color: var(--accent-blue); margin-bottom: 8px;">⚡ Преимущества</div>
                    <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: var(--text-secondary); line-height: 1.8;">
                        <li>Решение в течение 24 часов</li>
                        <li>Сумма до 50 млн ₽</li>
                        <li>Срок до 7 лет</li>
                        <li>Без залога при сумме до 10 млн ₽</li>
                    </ul>
                </div>

                <div style="padding: 16px; background: var(--bg-secondary); border-radius: var(--radius-md); border: 1px solid var(--border-primary);">
                    <div style="font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">📞 Контакты для связи</div>
                    <div style="font-size: 13px; color: var(--text-secondary); line-height: 1.8;">
                        <div>Телефон: <strong style="color: var(--text-primary);">8 (800) 100-24-24</strong></div>
                        <div>Email: <strong style="color: var(--text-primary);">corp@vtb.ru</strong></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endif

<script>
    // ========== ФУНКЦИИ ДЛЯ КЛИЕНТА ==========
    
    // Обновление отображения ставки
    function updateRateDisplay() {
        const productSelect = document.getElementById('product_id');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const rate = selectedOption.getAttribute('data-rate') || '16';
        document.getElementById('rateDisplay').textContent = rate + '%';
        calculatePayment();
    }

    // Расчёт платежа
    function calculatePayment() {
        const amount = parseFloat(document.getElementById('amount').value) || 5000000;
        const term = parseInt(document.getElementById('term_months').value) || 24;
        
        const productSelect = document.getElementById('product_id');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const rate = parseFloat(selectedOption.getAttribute('data-rate')) || 16;
        
        const monthlyRate = rate / 100 / 12;
        let monthlyPayment;
        
        if (monthlyRate === 0) {
            monthlyPayment = amount / term;
        } else {
            monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, term) / 
                           (Math.pow(1 + monthlyRate, term) - 1);
        }
        
        document.getElementById('calcResult').textContent = 
            new Intl.NumberFormat('ru-RU').format(Math.round(monthlyPayment)) + ' ₽/мес';
        document.getElementById('rateDisplay').textContent = rate + '%';
    }

    // Управление списком файлов
    function updateFileList() {
        const input = document.getElementById('documents');
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
            
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <span class="file-item__name">
                    📎 ${file.name} (${sizeMB} МБ)
                </span>
                <span class="file-item__remove" onclick="removeFile(${i})">Удалить</span>
            `;
            fileList.appendChild(fileItem);
        }
    }

    function removeFile(index) {
        const input = document.getElementById('documents');
        const dt = new DataTransfer();
        
        for (let i = 0; i < input.files.length; i++) {
            if (i !== index) {
                dt.items.add(input.files[i]);
            }
        }
        
        input.files = dt.files;
        updateFileList();
    }

    // Инициализация для клиента
    if (document.getElementById('product_id')) {
        updateRateDisplay();
        calculatePayment();
    }

    // ========== ФУНКЦИИ ДЛЯ СОТРУДНИКА ==========
    
    // Данные заявок (в реальном проекте загружаются через AJAX)
    const applicationsData = @json($loanApplications->keyBy('id'));
    
    function openApplicationModal(appId) {
        const app = applicationsData[appId];
        if (!app) return;
        
        document.getElementById('modalTitle').textContent = 'Заявка #' + app.id;
        document.getElementById('modalCompany').textContent = app.client?.short_name || '—';
        document.getElementById('modalContact').textContent = app.client?.contact_person || '—';
        document.getElementById('modalProduct').textContent = app.credit_product?.name || '—';
        document.getElementById('modalAmount').textContent = new Intl.NumberFormat('ru-RU').format(app.amount) + ' ₽';
        document.getElementById('modalTerm').textContent = app.term_months + ' месяцев';
        document.getElementById('modalRate').textContent = (app.credit_product?.base_rate || '—') + '%';
        document.getElementById('modalDate').textContent = new Date(app.created_at).toLocaleDateString('ru-RU');
        
        // Статус
        let statusHtml = '';
        switch(app.status) {
            case 'pending': statusHtml = '<span class="chip chip-warning">На рассмотрении</span>'; break;
            case 'approved': statusHtml = '<span class="chip chip-success">Одобрена</span>'; break;
            case 'rejected': statusHtml = '<span class="chip chip-danger">Отклонена</span>'; break;
            default: statusHtml = app.status;
        }
        document.getElementById('modalStatus').innerHTML = statusHtml;
        
        // Расчёт платежа
        const rate = parseFloat(app.credit_product?.base_rate || 16);
        const monthlyRate = rate / 100 / 12;
        let monthlyPayment;
        if (monthlyRate === 0) {
            monthlyPayment = app.amount / app.term_months;
        } else {
            monthlyPayment = app.amount * monthlyRate * Math.pow(1 + monthlyRate, app.term_months) / 
                           (Math.pow(1 + monthlyRate, app.term_months) - 1);
        }
        document.getElementById('modalPayment').textContent = 
            new Intl.NumberFormat('ru-RU').format(Math.round(monthlyPayment)) + ' ₽';
        
        // Документы
        const docsHtml = app.documents?.length 
            ? app.documents.map(d => `<div>📎 ${d.original_name || 'Документ'}</div>`).join('')
            : 'Документы не приложены';
        document.getElementById('modalDocs').innerHTML = docsHtml;
        
        // Кнопки действий
        const actionsDiv = document.getElementById('modalActions');
        actionsDiv.innerHTML = '';
        
        if (app.status === 'pending') {
            actionsDiv.innerHTML = `
                <form action="/loans/${app.id}/approve" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-vtb btn-vtb--success">Одобрить заявку</button>
                </form>
                <form action="/loans/${app.id}/reject" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-vtb btn-vtb--danger">Отклонить</button>
                </form>
            `;
        }
        
        document.getElementById('applicationModal').classList.add('active');
    }
    
    function closeApplicationModal() {
        document.getElementById('applicationModal').classList.remove('active');
    }
    
    // Закрытие по клику вне модального окна
    document.getElementById('applicationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeApplicationModal();
        }
    });
</script>
@endsection