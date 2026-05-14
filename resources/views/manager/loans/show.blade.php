@extends('layouts.app')

@section('title', 'Заявка #' . $loan->id)
@section('page_title', 'Заявка #' . $loan->id)

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <div class="page-header__title">Заявка #{{ $loan->id }}</div>
        <div class="page-header__subtitle">
            Создана: {{ $loan->created_at->format('d.m.Y H:i') }} |
            Статус: 
            @if($loan->status == 'issued')
                <span class="status-pill status-pill--success">Выдан</span>
            @elseif($loan->status == 'pending')
                <span class="status-pill status-pill--warning">На рассмотрении</span>
            @elseif($loan->status == 'rejected')
                <span class="status-pill status-pill--error">Отклонён</span>
            @endif
        </div>
    </div>
    <a href="{{ route('manager.loans.index') }}" class="btn btn--ghost">← К списку заявок</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Информация о заявке -->
    <div class="card">
        <div class="card__title">Информация о заявке</div>
        <div class="divider"></div>
        
        <table style="width: 100%;">
            <tr>
                <td style="padding: 10px 0; color: #606981; width: 160px;">Клиент</td>
                <td>
                    <a href="{{ route('companies.show', $loan->client) }}" style="color: #3261EC; text-decoration: none; font-weight: 500;">
                        {{ $loan->client->short_name ?? '—' }}
                    </a>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Кредитный продукт</td>
                <td>
                    <span class="loan-tag">{{ $loan->creditProduct->name ?? '—' }}</span>
                    ({{ $loan->creditProduct->base_rate ?? '—' }}%)
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Сумма</td>
                <td class="text-mono text-strong" style="font-size: 18px;">
                    {{ number_format($loan->amount, 0, ',', ' ') }} ₽
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Срок</td>
                <td>{{ $loan->term_months ?? '—' }} месяцев</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Дата выдачи</td>
                <td>{{ $loan->issue_date->format('d.m.Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Цель кредита</td>
                <td>{{ $loan->purpose ?? '—' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Создал</td>
                <td>{{ $loan->creator->name ?? '—' }}</td>
            </tr>
            @if($loan->approved_by)
            <tr>
                <td style="padding: 10px 0; color: #606981;">Обработал</td>
                <td>{{ $loan->approver->name ?? '—' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Дата обработки</td>
                <td>{{ $loan->approved_at ? $loan->approved_at->format('d.m.Y H:i') : '—' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Контакты клиента -->
    <div class="card">
        <div class="card__title">Контакты клиента</div>
        <div class="divider"></div>
        
        @if($loan->client)
        <table style="width: 100%;">
            <tr>
                <td style="padding: 10px 0; color: #606981; width: 160px;">Компания</td>
                <td>{{ $loan->client->full_name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">ИНН</td>
                <td class="text-mono">{{ $loan->client->inn }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Контактное лицо</td>
                <td>{{ $loan->contact_person ?? $loan->client->contact_person }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Телефон</td>
                <td>{{ $loan->contact_phone ?? $loan->client->phone }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #606981;">Адрес</td>
                <td>{{ $loan->client->legal_address }}</td>
            </tr>
        </table>
        @else
        <p style="color: #9CA3AF;">Клиент не найден</p>
        @endif
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card__title">📎 Документы к заявке</div>
    <div class="divider"></div>
    
    @php
        // Ищем документы, связанные с этой кредитной заявкой
        $documents = \App\Models\RegistrationDocument::whereIn('registration_request_id', 
            \App\Models\RegistrationRequest::where('request_type', 'loan_application')
                ->where('existing_client_id', $loan->client_id)
                ->pluck('id')
        )->orderBy('created_at', 'desc')->get();
    @endphp
    
    @if($documents->count() > 0)
        <div class="table-wrapper">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Название файла</th>
                        <th>Размер</th>
                        <th>Дата загрузки</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                    <tr>
                        <td style="word-break: break-all;">📄 {{ $doc->file_name }}</td>
                        <td class="text-mono" style="white-space: nowrap;">{{ $doc->formatted_size }}</td>
                        <td style="white-space: nowrap;">{{ $doc->created_at->format('d.m.Y H:i') }}</td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('manager.registrations.download-document', [
                                'registration' => $doc->registration_request_id, 
                                'document' => $doc
                            ]) }}" 
                               class="btn btn--ghost btn--sm" 
                               target="_blank"
                               style="padding: 6px 12px; font-size: 12px;">
                                📥 Скачать
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #9CA3AF;">
            <div style="font-size: 48px; margin-bottom: 12px;">📂</div>
            <p>Документы не приложены к заявке</p>
            <p style="font-size: 13px;">Клиент не загрузил дополнительные документы</p>
        </div>
    @endif
</div>

<!-- Кнопки действий (только для pending) -->
@if($loan->status == 'pending')
<div class="card" style="margin-top: 20px;">
    <div style="display: flex; gap: 12px;">
        <!-- Кнопка одобрения -->
        <form action="{{ route('manager.loans.approve', $loan) }}" method="POST" style="flex: 1;">
            @csrf
            <button type="submit" class="btn btn--primary" style="width: 100%; background: #16A34A;">
                Одобрить заявку
            </button>
        </form>
        
        <!-- Кнопка отклонения -->
        <button onclick="document.getElementById('rejectForm').style.display='block'" 
                class="btn" style="flex: 1; border: 2px solid #DC2626; color: #DC2626; background: white;">
            Отклонить заявку
        </button>
    </div>
    
    <!-- Форма отклонения (изначально скрыта) -->
    <form id="rejectForm" action="{{ route('manager.loans.reject', $loan) }}" method="POST" style="display: none; margin-top: 16px;">
        @csrf
        <div class="form-group">
            <label>Причина отклонения</label>
            <textarea name="rejection_reason" rows="3" required placeholder="Укажите причину отклонения заявки..."></textarea>
        </div>
        <div style="display: flex; gap: 8px; margin-top: 8px;">
            <button type="submit" class="btn btn--danger">Подтвердить отклонение</button>
            <button type="button" onclick="document.getElementById('rejectForm').style.display='none'" class="btn btn--ghost">Отмена</button>
        </div>
    </form>
</div>
@endif

<!-- Причина отклонения -->
@if($loan->status == 'rejected' && $loan->notes)
<div class="card" style="margin-top: 20px; background: #FEF2F2; border: 1px solid #FECACA;">
    <div style="color: #991B1B; font-weight: 600; margin-bottom: 8px;">Причина отклонения:</div>
    <div style="color: #7F1D1D;">{{ $loan->notes }}</div>
    <div style="margin-top: 8px; font-size: 12px; color: #991B1B;">
        Отклонено: {{ $loan->approved_at ? $loan->approved_at->format('d.m.Y H:i') : '—' }}
        сотрудником {{ $loan->approver->name ?? '—' }}
    </div>
</div>
@endif

<!-- Если выдана -->
@if($loan->status == 'issued')
<div class="card" style="margin-top: 20px; background: #F0FDF4; border: 1px solid #BBF7D0;">
    <div style="color: #166534;">
        Заявка одобрена и кредит выдан
    </div>
    <div style="margin-top: 8px; color: #166534; font-size: 13px;">
        Выдан: {{ $loan->approved_at ? $loan->approved_at->format('d.m.Y H:i') : '—' }}
    </div>
</div>
@endif

<style>
    .table-wrapper {
        overflow-x: auto;
    }
    .table-wrapper table {
        width: 100%;
        border-collapse: collapse;
    }
    .table-wrapper th {
        text-align: left;
        padding: 12px 0;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        border-bottom: 1px solid #E5E7EB;
    }
    .table-wrapper td {
        padding: 12px 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .table-wrapper tr:last-child td {
        border-bottom: none;
    }
    .btn--sm {
        padding: 6px 12px;
        font-size: 12px;
    }
</style>
@endsection