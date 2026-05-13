@extends('layouts.app')

@section('title', 'Реестр кредитов ЮЛ')
@section('page_title', 'Реестр кредитов ЮЛ')

@section('content')
<div class="page-header">
    <div class="page-header__title">Реестр кредитных операций</div>
    <div class="page-header__subtitle">
        Факты выдачи кредитов: клиент, вид кредита, сумма и дата.
    </div>
</div>

<!-- Реестр с фильтрами -->
<section class="card">
    <div class="card__header">
        <div>
            <div class="card__meta">Операционный реестр</div>
            <div class="card__title">Все зарегистрированные выдачи</div>
        </div>
        <span class="pill-soft">Период: 2025–2026</span>
    </div>

    <div class="divider"></div>

    <form method="GET" action="{{ route('loans.index') }}" style="margin-bottom: 16px;">
        <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <div>
                <label style="font-size: 12px; font-weight: 500; color: var(--text-muted);">Фильтр по виду кредита:</label>
                <select name="product_id" class="filter-select" style="padding: 7px 10px; border-radius: 999px; border: 1px solid var(--border-subtle);">
                    <option value="">Все виды</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size: 12px; font-weight: 500; color: var(--text-muted);">Фильтр по клиенту:</label>
                <select name="client_id" class="filter-select" style="padding: 7px 10px; border-radius: 999px; border: 1px solid var(--border-subtle);">
                    <option value="">Все клиенты</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->short_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn--ghost" style="padding: 7px 16px; font-size: 13px;">Применить</button>
            <a href="{{ route('loans.index') }}" style="font-size: 12px; color: var(--text-muted); text-decoration: none;">Сбросить</a>
        </div>
    </form>

    <!-- Таблица -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Клиент</th>
                    <th>Вид кредита</th>
                    <th>Сумма</th>
                    <th>Дата выдачи</th>
                    <th>Ставка</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td>
                        <a href="{{ route('clients.show', $loan->client) }}" style="color: var(--text-main); text-decoration: none; font-weight: 500;">
                            {{ $loan->client->short_name ?? '—' }}
                        </a>
                    </td>
                    <td><span class="loan-tag">{{ $loan->creditProduct->name ?? '—' }}</span></td>
                    <td><span class="text-strong text-mono">{{ number_format($loan->amount, 0, ',', ' ') }} ₽</span></td>
                    <td>{{ $loan->issue_date->format('d.m.Y') }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 999px; background: #F9FAFB; border: 1px solid #E5E7EB; font-size: 12px;">
                            {{ $loan->creditProduct->base_rate ?? '—' }}%
                        </span>
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 999px; font-size: 12px; 
                            @if($loan->status == 'issued') background: #DCFCE7; color: #166534; 
                            @elseif($loan->status == 'pending') background: #FEF3C7; color: #92400E;
                            @else background: #E5E7EB; color: #374151; @endif">
                            {{ $loan->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: var(--text-soft);">
                        По заданным фильтрам операций не найдено
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($loans->count() > 0)
    <div style="margin-top: 12px; font-size: 12px; color: var(--text-muted);">
        Отфильтровано: {{ $loans->count() }} операций, 
        объём {{ number_format($loans->sum('amount'), 0, ',', ' ') }} ₽
    </div>
    @endif
</section>
@endsection