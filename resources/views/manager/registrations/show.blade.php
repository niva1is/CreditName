@extends('layouts.app')

@section('title', 'Заявка #' . $registration->id)

@section('content')
<div class="page-header">
    <div>
        <div class="page-header__title">Заявка на регистрацию #{{ $registration->id }}</div>
        <div class="page-header__subtitle">
            Подана: {{ $registration->created_at->format('d.m.Y H:i') }} | 
            Статус: 
            @if($registration->status == 'pending')
                <span style="background: #FEF3C7; color: #92400E; padding: 2px 8px; border-radius: 999px;">Ожидает рассмотрения</span>
            @elseif($registration->status == 'approved')
                <span style="background: #DCFCE7; color: #166534; padding: 2px 8px; border-radius: 999px;">Одобрена</span>
            @else
                <span style="background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 999px;">Отклонена</span>
            @endif
        </div>
    </div>
    <a href="{{ route('manager.registrations.index') }}" class="btn btn--ghost">← Назад к списку</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Данные компании -->
    <div class="card">
        <div class="card__title">📋 Данные компании</div>
        <div class="divider"></div>
        
        <table style="width: 100%;">
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Полное наименование</td>
                <td style="font-weight: 500;">{{ $registration->company_full_name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Краткое наименование</td>
                <td style="font-weight: 500;">{{ $registration->company_short_name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">ИНН</td>
                <td>{{ $registration->inn }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">ОГРН</td>
                <td>{{ $registration->ogrn }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Форма собственности</td>
                <td>{{ $registration->ownership_form }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Юридический адрес</td>
                <td>{{ $registration->legal_address }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Телефон</td>
                <td>{{ $registration->phone }}</td>
            </tr>
        </table>
    </div>

    <!-- Контактное лицо и данные для входа -->
    <div class="card">
        <div class="card__title">👤 Контактное лицо и доступ</div>
        <div class="divider"></div>
        
        <table style="width: 100%;">
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Контактное лицо</td>
                <td style="font-weight: 500;">{{ $registration->contact_person }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Email для связи</td>
                <td>{{ $registration->contact_email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Email для входа</td>
                <td><strong>{{ $registration->email }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Статус</td>
                <td>
                    @if($registration->status == 'pending')
                        <span style="background: #FEF3C7; color: #92400E; padding: 4px 12px; border-radius: 999px; font-size: 12px;">⏳ Ожидает</span>
                    @elseif($registration->status == 'approved')
                        <span style="background: #DCFCE7; color: #166534; padding: 4px 12px; border-radius: 999px; font-size: 12px;">✅ Одобрена</span>
                    @else
                        <span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 999px; font-size: 12px;">❌ Отклонена</span>
                    @endif
                </td>
            </tr>
            @if($registration->approved_by)
            <tr>
                <td style="padding: 8px 0; color: var(--text-muted); font-size: 13px;">Обработал</td>
                <td>{{ $registration->approver->name ?? '—' }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>

<!-- Действия -->
@if($registration->status == 'pending')
<div class="card" style="margin-top: 20px;">
    <div style="display: flex; gap: 12px;">
        <!-- Кнопка одобрения -->
        <form action="{{ route('manager.registrations.approve', $registration) }}" method="POST" style="flex: 1;">
            @csrf
            <button type="submit" class="btn btn--primary" style="width: 100%; background: #16A34A;">
                ✅ Одобрить заявку
            </button>
        </form>
        
        <!-- Кнопка отклонения с причиной -->
        <button onclick="document.getElementById('rejectForm').style.display='block'" class="btn" style="flex: 1; border-color: #DC2626; color: #DC2626;">
            ❌ Отклонить заявку
        </button>
    </div>
    
    <form id="rejectForm" action="{{ route('manager.registrations.reject', $registration) }}" method="POST" style="display: none; margin-top: 16px;">
        @csrf
        <div class="form-group">
            <label>Причина отклонения</label>
            <textarea name="rejection_reason" rows="3" required placeholder="Укажите причину отклонения заявки..."></textarea>
        </div>
        <button type="submit" class="btn" style="background: #DC2626; color: white; margin-top: 8px;">
            Подтвердить отклонение
        </button>
    </form>
</div>
@endif

<!-- Документы -->
@if($registration->documents->count() > 0)
<div class="card" style="margin-top: 20px;">
    <div class="card__title">📎 Загруженные документы ({{ $registration->documents->count() }})</div>
    <div class="divider"></div>
    
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>Тип документа</th>
                <th>Название файла</th>
                <th>Размер</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registration->documents as $doc)
            <tr>
                <td>
                    @switch($doc->document_type)
                        @case('charter') Устав @break
                        @case('inn_certificate') Свидетельство ИНН @break
                        @case('ogrn_certificate') Свидетельство ОГРН @break
                        @case('director_order') Приказ о назначении @break
                        @default {{ $doc->document_type }}
                    @endswitch
                </td>
                <td>{{ $doc->file_name }}</td>
                <td>{{ $doc->formatted_size }}</td>
                <td>
                    <a href="{{ route('manager.registrations.download-document', ['registration' => $registration, 'document' => $doc]) }}" 
                       class="btn btn--ghost" style="padding: 6px 12px; font-size: 12px;" target="_blank">
                        📥 Скачать
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="card" style="margin-top: 20px; text-align: center; color: var(--text-soft);">
    📎 Документы не загружены
</div>
@endif

<!-- Если отклонена - показать причину -->
@if($registration->status == 'rejected' && $registration->rejection_reason)
<div class="card" style="margin-top: 20px; background: #FEF2F2; border-color: #FECACA;">
    <div style="color: #991B1B; font-weight: 600; margin-bottom: 8px;">❌ Причина отклонения:</div>
    <div style="color: #7F1D1D;">{{ $registration->rejection_reason }}</div>
    <div style="margin-top: 8px; font-size: 12px; color: #991B1B;">
        Отклонено: {{ $registration->approved_at->format('d.m.Y H:i') }}
        сотрудником {{ $registration->approver->name ?? '—' }}
    </div>
</div>
@endif

<!-- Если одобрена -->
@if($registration->status == 'approved')
<div class="card" style="margin-top: 20px; background: #F0FDF4; border-color: #BBF7D0;">
    <div style="color: #166534;">
        ✅ Заявка одобрена {{ $registration->approved_at->format('d.m.Y H:i') }}
    </div>
    <div style="margin-top: 8px; color: #166534; font-size: 14px;">
        Создана учётная запись клиента. Логин: <strong>{{ $registration->email }}</strong>
    </div>
</div>
@endif

<style>
    .card__title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(148,163,184,0.35);
    }
    table tr {
        border-bottom: 1px solid #F3F4F6;
    }
    table tr:last-child {
        border-bottom: none;
    }
</style>
@endsection