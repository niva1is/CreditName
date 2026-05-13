{{-- resources/views/companies/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Компании')

@section('content')
<div class="page-header">
    <div>
        <div class="page-header__title">Компании и клиенты</div>
        <div class="page-header__subtitle">Реестр юридических лиц</div>
    </div>
    @if(auth()->user()->hasAnyRole(['admin','supervisor','credit_manager']))
        <a href="#" class="btn btn--primary">+ Добавить компанию</a>
    @endif
</div>

<!-- Поиск и фильтры -->
<div style="margin: 24px 0; display: flex; gap: 12px; flex-wrap: wrap;">
    <form method="GET" style="flex: 1; min-width: 300px;">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Поиск по названию, ИНН..." 
               style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #d1d5db;">
    </form>
    
    <select name="ownership" onchange="this.form.submit()" style="padding: 12px; border-radius: 12px;">
        <option value="">Все формы собственности</option>
        <option value="ООО" {{ request('ownership')=='ООО'?'selected':'' }}>ООО</option>
        <option value="АО" {{ request('ownership')=='АО'?'selected':'' }}>АО</option>
        <option value="ПАО" {{ request('ownership')=='ПАО'?'selected':'' }}>ПАО</option>
    </select>
</div>

<!-- Таблица компаний -->
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Краткое название</th>
                    <th>ИНН</th>
                    <th>Контактное лицо</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td><strong>{{ $client->short_name }}</strong></td>
                    <td class="text-mono">{{ $client->inn }}</td>
                    <td>{{ $client->contact_person ?? '—' }}</td>
                    <td>{{ $client->phone ?? '—' }}</td>
                    <td>
                        @if($client->status == 'active')
                            <span class="chip chip-success">Активен</span>
                        @else
                            <span class="chip chip-warning">Неактивен</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('companies.show', $client) }}" class="btn btn--ghost btn--sm">Открыть</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:60px;">Компании не найдены</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $clients->links() }}
</div>

@endsection