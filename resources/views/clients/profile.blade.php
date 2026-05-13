@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
@php
    $user = auth()->user();
    $client = \App\Models\Client::where('user_id', $user->id)->first();
    $loans = $client ? $client->loanApplications()->with('creditProduct')->orderBy('created_at', 'desc')->get() : collect();
    $documents = $client ? \App\Models\RegistrationDocument::whereIn('registration_request_id', 
        \App\Models\RegistrationRequest::where('existing_client_id', $client->id)
            ->orWhere(function($q) use ($client) {
                $q->where('inn', $client->inn)->where('ogrn', $client->ogrn);
            })
            ->pluck('id')
    )->orderBy('created_at', 'desc')->get() : collect();
@endphp

@if(!$client)
    <div class="card" style="text-align: center; padding: 40px;">
        <div style="font-size: 48px;">❌</div>
        <h2>Профиль компании не найден</h2>
        <p style="color: var(--text-muted);">Обратитесь к менеджеру банка</p>
    </div>
@else

<!-- БЛОК 1: Информация о пользователе + смена пароля -->
<div class="card" style="margin-bottom: 20px;">
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
        <div style="width: 60px; height: 60px; border-radius: 999px; background: linear-gradient(135deg, #0F5ECC, #38BDF8); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700;">
            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h2 style="margin: 0;">{{ $user->name }}</h2>
            <p style="color: var(--text-muted); margin: 4px 0;">{{ $user->email }}</p>
        </div>
        <button onclick="toggleSection('passwordSection')" class="btn btn--ghost" style="margin-left: auto; padding: 8px 16px; font-size: 13px;">🔒 Сменить пароль</button>
    </div>
    
    <!-- Смена пароля -->
    <div id="passwordSection" style="display: none; padding: 20px; background: #F9FAFB; border-radius: 12px;">
        <h4 style="margin-bottom: 12px;">Сменить пароль</h4>
        <form action="{{ route('client.password.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label>Текущий пароль</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>Новый пароль</label>
                    <input type="password" name="new_password" required minlength="8">
                </div>
                <div class="form-group">
                    <label>Подтверждение нового пароля</label>
                    <input type="password" name="new_password_confirmation" required minlength="8">
                </div>
            </div>
            <div style="margin-top: 12px; display: flex; gap: 8px;">
                <button type="submit" class="btn btn--primary">Обновить пароль</button>
                <button type="button" onclick="toggleSection('passwordSection')" class="btn btn--ghost">Отмена</button>
            </div>
        </form>
    </div>
    
    <!-- Статистика -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
        <div style="padding: 12px; background: #F9FAFB; border-radius: 12px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #0F5ECC;">{{ $loans->count() }}</div>
            <div style="font-size: 12px; color: var(--text-muted);">Кредитов</div>
        </div>
        <div style="padding: 12px; background: #F9FAFB; border-radius: 12px; text-align: center;">
            <div style="font-size: 20px; font-weight: 700; color: #16A34A;">{{ number_format($loans->sum('amount') / 1000000, 1) }} млн</div>
            <div style="font-size: 12px; color: var(--text-muted);">Общая сумма</div>
        </div>
        <div style="padding: 12px; background: #F9FAFB; border-radius: 12px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #F59E0B;">{{ $documents->count() }}</div>
            <div style="font-size: 12px; color: var(--text-muted);">Документов</div>
        </div>
    </div>
</div>

<!-- БЛОК 2: Данные компании -->
<div class="card" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div class="card__title">🏢 {{ $client->short_name }}</div>
        <button onclick="toggleEdit()" class="btn btn--ghost" style="padding: 8px 16px; font-size: 13px;">✏️ Редактировать</button>
    </div>
    <div class="divider"></div>
    
    <div id="viewMode">
        <table style="width: 100%;">
            <tr><td style="color: var(--text-muted); padding: 8px 0; width: 200px;">Полное наименование</td><td>{{ $client->full_name }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">ИНН</td><td class="text-mono">{{ $client->inn }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">ОГРН</td><td class="text-mono">{{ $client->ogrn }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Форма</td><td>{{ $client->ownership_form }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Адрес</td><td>{{ $client->legal_address }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Телефон</td><td>{{ $client->phone }}</td></tr>
            <tr><td style="color: var(--text-muted); padding: 8px 0;">Контактное лицо</td><td>{{ $client->contact_person }}</td></tr>
        </table>
    </div>
    
    <div id="editMode" style="display: none;">
        <form action="{{ route('client.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group"><label>Полное наименование</label><input type="text" name="full_name" value="{{ $client->full_name }}"></div>
                <div class="form-group"><label>Краткое наименование</label><input type="text" name="short_name" value="{{ $client->short_name }}"></div>
                <div class="form-group"><label>Юридический адрес</label><textarea name="legal_address" rows="2">{{ $client->legal_address }}</textarea></div>
                <div class="form-group"><label>Телефон</label><input type="text" name="phone" value="{{ $client->phone }}"></div>
                <div class="form-group"><label>Контактное лицо</label><input type="text" name="contact_person" value="{{ $client->contact_person }}"></div>
            </div>
            <div style="margin-top: 12px; display: flex; gap: 8px;">
                <button type="submit" class="btn btn--primary">💾 Сохранить</button>
                <button type="button" onclick="toggleEdit()" class="btn btn--ghost">Отмена</button>
            </div>
        </form>
    </div>
</div>

<!-- БЛОК 3: Документы -->
<div class="card" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div class="card__title">📎 Документы</div>
        <div style="display: flex; gap: 8px;">
            <button onclick="toggleSection('uploadSection')" class="btn btn--primary" style="padding: 8px 16px; font-size: 13px;">+ Загрузить</button>
        </div>
    </div>
    <div class="divider"></div>
    
    <!-- Форма загрузки -->
    <div id="uploadSection" style="display: none; margin-bottom: 16px; padding: 16px; background: #F9FAFB; border-radius: 12px; border: 2px dashed #CBD5E1;">
        <form action="{{ route('client.documents.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="uploadFields">
                <div class="upload-row" style="display: flex; gap: 12px; margin-bottom: 12px;">
                    <select name="document_types[]" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB;">
                        <option value="">Тип документа</option>
                        <option value="charter">Устав</option>
                        <option value="inn_certificate">Свидетельство ИНН</option>
                        <option value="ogrn_certificate">Свидетельство ОГРН</option>
                        <option value="other">Прочее</option>
                    </select>
                    <input type="file" name="documents[]" required style="flex: 2;">
                    <button type="button" onclick="this.closest('.upload-row').remove()" style="background: #DC2626; color: white; border: none; padding: 8px 12px; border-radius: 8px;">✕</button>
                </div>
            </div>
            <button type="button" onclick="addUploadRow()" style="width: 100%; padding: 8px; background: #EFF6FF; border: 1px dashed #0F5ECC; border-radius: 8px; cursor: pointer;">+ Добавить ещё</button>
            <div style="margin-top: 12px; display: flex; gap: 8px;">
                <button type="submit" class="btn btn--primary">📤 Загрузить</button>
                <button type="button" onclick="toggleSection('uploadSection')" class="btn btn--ghost">Отмена</button>
            </div>
        </form>
    </div>
    
    @if($documents->count() > 0)
        <table style="width: 100%;">
            <thead><tr><th>Тип</th><th>Файл</th><th>Размер</th><th>Действия</th></tr></thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td>{{ $doc->document_type }}</td>
                    <td>{{ $doc->file_name }}</td>
                    <td>{{ $doc->formatted_size }}</td>
                    <td>
                        <a href="{{ route('manager.registrations.download-document', ['registration' => $doc->registration_request_id, 'document' => $doc]) }}" target="_blank" style="color: #0F5ECC;">📥</a>
                        <form action="{{ route('client.documents.delete', $doc) }}" method="POST" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Удалить?')" style="background: none; border: none; color: #DC2626; cursor: pointer;">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: var(--text-soft); padding: 20px;">Документы не загружены</p>
    @endif
</div>

<!-- БЛОК 4: Кредиты -->
<div class="card">
    <div class="card__title">💰 Мои кредиты</div>
    <div class="divider"></div>
    
    @if($loans->count() > 0)
        <table style="width: 100%;">
            <thead><tr><th>Продукт</th><th>Сумма</th><th>Дата</th><th>Ставка</th><th>Статус</th></tr></thead>
            <tbody>
                @foreach($loans as $loan)
                <tr>
                    <td><span class="loan-tag">{{ $loan->creditProduct->name ?? '—' }}</span></td>
                    <td class="text-mono text-strong">{{ number_format($loan->amount, 0, ',', ' ') }} ₽</td>
                    <td>{{ $loan->issue_date->format('d.m.Y') }}</td>
                    <td>{{ $loan->creditProduct->base_rate ?? '—' }}%</td>
                    <td>
                        @if($loan->status == 'issued')
                            <span style="background: #DCFCE7; color: #166534; padding: 4px 8px; border-radius: 999px; font-size: 12px;">✅ Выдан</span>
                        @else
                            <span style="background: #FEF3C7; padding: 4px 8px; border-radius: 999px; font-size: 12px;">{{ $loan->status }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px;">
            <div style="font-size: 48px;">💰</div>
            <p>У вас пока нет кредитов</p>
            <a href="{{ route('loans.create') }}" class="btn btn--primary" style="margin-top: 12px;">Оформить кредит</a>
        </div>
    @endif
</div>

@endif

<script>
function toggleSection(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
function toggleEdit() {
    document.getElementById('viewMode').style.display = 
        document.getElementById('viewMode').style.display === 'none' ? 'block' : 'none';
    document.getElementById('editMode').style.display = 
        document.getElementById('editMode').style.display === 'none' ? 'block' : 'none';
}
function addUploadRow() {
    const container = document.getElementById('uploadFields');
    const row = document.createElement('div');
    row.className = 'upload-row';
    row.style.cssText = 'display: flex; gap: 12px; margin-bottom: 12px;';
    row.innerHTML = `
        <select name="document_types[]" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB;">
            <option value="">Тип</option>
            <option value="charter">Устав</option>
            <option value="inn_certificate">Свидетельство ИНН</option>
            <option value="ogrn_certificate">Свидетельство ОГРН</option>
            <option value="other">Прочее</option>
        </select>
        <input type="file" name="documents[]" required style="flex: 2;">
        <button type="button" onclick="this.closest('.upload-row').remove()" style="background: #DC2626; color: white; border: none; padding: 8px 12px; border-radius: 8px;">✕</button>
    `;
    container.appendChild(row);
}
</script>
@endsection