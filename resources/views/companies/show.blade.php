@extends('layouts.app')

@section('title', $client->short_name)

@section('content')
<div class="page-header">
    <div>
        <div class="page-header__title">{{ $client->short_name }}</div>
        <div class="page-header__subtitle">ИНН: {{ $client->inn }} | ОГРН: {{ $client->ogrn }}</div>
    </div>
    <div style="display: flex; gap: 10px;">
        @if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
            <a href="{{ route('companies.edit', $client) }}" class="btn btn--primary">✏️ Редактировать</a>
        @endif
        <a href="{{ route('companies.index') }}" class="btn btn--ghost">← К списку</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Основная информация -->
    <div class="card">
        <div class="card__title">📋 Основная информация</div>
        <div class="divider"></div>
        <table style="width: 100%;">
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Полное наименование</td><td>{{ $client->full_name }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Краткое наименование</td><td>{{ $client->short_name }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Форма собственности</td><td>{{ $client->ownership_form }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">ИНН</td><td class="text-mono">{{ $client->inn }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">ОГРН</td><td class="text-mono">{{ $client->ogrn }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Юридический адрес</td><td>{{ $client->legal_address }}</td></tr>
        </table>
    </div>

    <!-- Контакты -->
    <div class="card">
        <div class="card__title">📞 Контактная информация</div>
        <div class="divider"></div>
        <table style="width: 100%;">
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Контактное лицо</td><td>{{ $client->contact_person }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Телефон</td><td>{{ $client->phone }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Email (логин)</td><td>{{ $client->user->email ?? '—' }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Статус</td>
                <td>
                    @if($client->status == 'active')
                        <span style="background: #DCFCE7; color: #166534; padding: 4px 12px; border-radius: 999px;">✅ Активна</span>
                    @else
                        <span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 999px;">❌ Заблокирована</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
<!-- Документы компании -->
<div class="card" style="margin-top: 20px;">
    <div class="card__header" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="card__title">📎 Документы компании</div>
        @if(auth()->user()->hasAnyRole(['admin', 'supervisor', 'credit_manager']))
            <a href="{{ route('companies.edit', $client) }}" class="btn btn--ghost" style="padding: 8px 16px; font-size: 13px;">
                ✏️ Управлять документами
            </a>
        @endif
    </div>
    <div class="divider"></div>
    
    @php
        $documents = \App\Models\RegistrationDocument::whereIn('registration_request_id', 
            \App\Models\RegistrationRequest::where('existing_client_id', $client->id)
                ->orWhere(function($q) use ($client) {
                    $q->where('inn', $client->inn)->where('ogrn', $client->ogrn);
                })
                ->pluck('id')
        )->orderBy('created_at', 'desc')->get();
    @endphp
    
    @if($documents->count() > 0)
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Тип документа</th>
                    <th>Файл</th>
                    <th>Размер</th>
                    <th>Дата загрузки</th>
                    <th>Скачать</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 999px; background: #EFF6FF; color: #1D4ED8; font-size: 12px;">
                            @switch($doc->document_type)
                                @case('charter') 📋 Устав @break
                                @case('inn_certificate') 🏷️ Свидетельство ИНН @break
                                @case('ogrn_certificate') 🏷️ Свидетельство ОГРН @break
                                @case('director_order') 👔 Приказ о назначении @break
                                @case('ownership_certificate') 🏠 Свидетельство собственности @break
                                @case('power_of_attorney') 📝 Доверенность @break
                                @default 📄 {{ $doc->document_type }}
                            @endswitch
                        </span>
                    </td>
                    <td style="word-break: break-all;">{{ $doc->file_name }}</td>
                    <td class="text-mono" style="white-space: nowrap;">{{ $doc->formatted_size }}</td>
                    <td style="white-space: nowrap;">{{ $doc->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <a href="{{ route('manager.registrations.download-document', ['registration' => $doc->registration_request_id, 'document' => $doc]) }}" 
                           style="color: #0F5ECC; text-decoration: none;" target="_blank" title="Скачать">
                            📥 Скачать
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: var(--text-soft);">
            <div style="font-size: 48px; margin-bottom: 12px;">📂</div>
            <p>Документы не загружены</p>
        </div>
    @endif
</div>
<!-- Кредиты компании -->
<div class="card" style="margin-top: 20px;">
    <div class="card__title">💰 Кредитная история</div>
    <div class="divider"></div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Продукт</th>
                    <th>Сумма</th>
                    <th>Дата выдачи</th>
                    <th>Ставка</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->loanApplications as $loan)
                <tr>
                    <td><span class="loan-tag">{{ $loan->creditProduct->name ?? '—' }}</span></td>
                    <td class="text-mono text-strong">{{ number_format($loan->amount, 0, ',', ' ') }} ₽</td>
                    <td>{{ $loan->issue_date->format('d.m.Y') }}</td>
                    <td>{{ $loan->creditProduct->base_rate ?? '—' }}%</td>
                    <td>
                        @if($loan->status == 'issued')
                            <span style="background: #DCFCE7; color: #166534; padding: 4px 8px; border-radius: 999px; font-size: 12px;">Выдан</span>
                        @else
                            <span style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 999px; font-size: 12px;">{{ $loan->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align: center; padding: 20px;">Нет кредитов</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection