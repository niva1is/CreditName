@extends('layouts.app')

@section('title', 'Редактирование: ' . $client->short_name)

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: start;">
    <div>
        <div class="page-header__title">✏️ Редактирование компании</div>
        <div class="page-header__subtitle">{{ $client->short_name }}</div>
    </div>
    <a href="{{ route('companies.show', $client) }}" class="btn btn--ghost">← Назад к карточке</a>
</div>

<!-- Форма редактирования -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card__title">📋 Данные компании</div>
    <div class="divider"></div>
    
    <form action="{{ route('companies.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label>Полное наименование</label>
                <input type="text" name="full_name" value="{{ old('full_name', $client->full_name) }}" required>
            </div>
            
            <div class="form-group">
                <label>Краткое наименование</label>
                <input type="text" name="short_name" value="{{ old('short_name', $client->short_name) }}" required>
            </div>
            
            <div class="form-group">
                <label>Форма собственности</label>
                <select name="ownership_form" required>
                    <option value="ООО" {{ $client->ownership_form == 'ООО' ? 'selected' : '' }}>ООО</option>
                    <option value="АО" {{ $client->ownership_form == 'АО' ? 'selected' : '' }}>АО</option>
                    <option value="ПАО" {{ $client->ownership_form == 'ПАО' ? 'selected' : '' }}>ПАО</option>
                    <option value="ЗАО" {{ $client->ownership_form == 'ЗАО' ? 'selected' : '' }}>ЗАО</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>ИНН</label>
                <input type="text" value="{{ $client->inn }}" readonly style="background: #F3F4F6; color: #6B7280;">
            </div>
            
            <div class="form-group">
                <label>ОГРН</label>
                <input type="text" value="{{ $client->ogrn }}" readonly style="background: #F3F4F6; color: #6B7280;">
            </div>
            
            <div class="form-group">
                <label>Юридический адрес</label>
                <textarea name="legal_address" rows="2" required>{{ old('legal_address', $client->legal_address) }}</textarea>
            </div>
            
            <div class="form-group">
                <label>Телефон</label>
                <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" required>
            </div>
            
            <div class="form-group">
                <label>Контактное лицо</label>
                <input type="text" name="contact_person" value="{{ old('contact_person', $client->contact_person) }}" required>
            </div>
            
            <div class="form-group">
                <label>Email (логин)</label>
                <input type="text" value="{{ $client->user->email ?? 'Не привязан' }}" readonly style="background: #F3F4F6; color: #6B7280;">
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn--primary">💾 Сохранить изменения</button>
        </div>
    </form>
</div>

<!-- 👇 ЗАГРУЗКА И УДАЛЕНИЕ ДОКУМЕНТОВ -->
<div class="card">
    <div class="card__header" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="card__title">📎 Документы компании</div>
        <button onclick="toggleUploadForm()" class="btn btn--primary" style="padding: 8px 16px; font-size: 13px;">
            + Добавить документ
        </button>
    </div>
    <div class="divider"></div>
    
    <!-- Форма загрузки -->
    <div id="uploadForm" style="display: none; margin-bottom: 20px; padding: 20px; background: #F0F9FF; border: 2px dashed #0F5ECC; border-radius: 16px;">
        <h4 style="margin-bottom: 12px;">📤 Загрузить новый документ</h4>
        <form action="{{ route('companies.upload-document', $client) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="uploadFields">
                <div class="upload-row" style="display: flex; gap: 12px; align-items: end; margin-bottom: 12px;">
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label>Тип документа</label>
                        <select name="document_type[]" required>
                            <option value="">Выберите тип...</option>
                            <option value="charter">Устав</option>
                            <option value="inn_certificate">Свидетельство ИНН</option>
                            <option value="ogrn_certificate">Свидетельство ОГРН</option>
                            <option value="director_order">Приказ о назначении</option>
                            <option value="ownership_certificate">Свидетельство собственности</option>
                            <option value="power_of_attorney">Доверенность</option>
                            <option value="other">Прочее</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 2; margin-bottom: 0;">
                        <label>Файл</label>
                        <input type="file" name="document[]" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </div>
                    <button type="button" onclick="removeUploadRow(this)" style="background: #DC2626; color: white; border: none; padding: 10px 14px; border-radius: 8px; cursor: pointer;">✕</button>
                </div>
            </div>
            <button type="button" onclick="addUploadRow()" style="background: #EFF6FF; color: #0F5ECC; border: 1px dashed #0F5ECC; padding: 8px 16px; border-radius: 8px; cursor: pointer; margin-bottom: 12px; width: 100%;">
                + Добавить ещё документ
            </button>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn--primary">📤 Загрузить</button>
                <button type="button" onclick="toggleUploadForm()" class="btn btn--ghost">Отмена</button>
            </div>
        </form>
    </div>
    
    <!-- Список существующих документов -->
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
                    <th>Действия</th>
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
                    <td style="white-space: nowrap;">
                        <a href="{{ route('manager.registrations.download-document', ['registration' => $doc->registration_request_id, 'document' => $doc]) }}" 
                           style="color: #0F5ECC; text-decoration: none; margin-right: 12px;" target="_blank" title="Скачать">
                            📥
                        </a>
                        <form action="{{ route('companies.delete-document', $doc) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="background: none; border: none; cursor: pointer; font-size: 16px;" 
                                    onclick="return confirm('Удалить документ «{{ $doc->file_name }}»?')"
                                    title="Удалить">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: var(--text-soft);">
            <div style="font-size: 48px; margin-bottom: 12px;">📂</div>
            <p>Документы не загружены</p>
            <p style="font-size: 13px;">Нажмите «Добавить документ» для загрузки</p>
        </div>
    @endif
</div>

<script>
function toggleUploadForm() {
    const form = document.getElementById('uploadForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function addUploadRow() {
    const container = document.getElementById('uploadFields');
    const row = document.createElement('div');
    row.className = 'upload-row';
    row.style.cssText = 'display: flex; gap: 12px; align-items: end; margin-bottom: 12px;';
    row.innerHTML = `
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <select name="document_type[]" required>
                <option value="">Выберите тип...</option>
                <option value="charter">Устав</option>
                <option value="inn_certificate">Свидетельство ИНН</option>
                <option value="ogrn_certificate">Свидетельство ОГРН</option>
                <option value="director_order">Приказ о назначении</option>
                <option value="ownership_certificate">Свидетельство собственности</option>
                <option value="power_of_attorney">Доверенность</option>
                <option value="other">Прочее</option>
            </select>
        </div>
        <div class="form-group" style="flex: 2; margin-bottom: 0;">
            <input type="file" name="document[]" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        </div>
        <button type="button" onclick="removeUploadRow(this)" style="background: #DC2626; color: white; border: none; padding: 10px 14px; border-radius: 8px; cursor: pointer;">✕</button>
    `;
    container.appendChild(row);
}

function removeUploadRow(btn) {
    btn.closest('.upload-row').remove();
}
</script>
@endsection