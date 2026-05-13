@extends('layouts.app')

@section('title', 'Заявки на кредит')
@section('page_title', 'Заявки на кредит')

@section('content')
<div class="page-header">
    <div class="page-header__title">Заявки на кредит</div>
    <div class="page-header__subtitle">Управление кредитными заявками</div>
</div>

<div class="card">
    <div style="margin-bottom: 16px; display: flex; gap: 8px;">
        <a href="{{ route('manager.loans.index') }}" class="btn btn--ghost btn--sm">Все</a>
        <a href="{{ route('manager.loans.index', ['status' => 'issued']) }}" class="btn btn--ghost btn--sm">Выданы</a>
        <a href="{{ route('manager.loans.index', ['status' => 'pending']) }}" class="btn btn--ghost btn--sm">На рассмотрении</a>
        <a href="{{ route('manager.loans.index', ['status' => 'rejected']) }}" class="btn btn--ghost btn--sm">Отклонены</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Продукт</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loans as $loan)
                <tr>
                    <td>#{{ $loan->id }}</td>
                    <td>{{ $loan->client->short_name ?? '—' }}</td>
                    <td><span class="loan-tag">{{ $loan->creditProduct->name ?? '—' }}</span></td>
                    <td class="text-mono text-strong">{{ number_format($loan->amount, 0, ',', ' ') }} ₽</td>
                    <td>{{ $loan->created_at->format('d.m.Y') }}</td>
                    <td>
                        @if($loan->status == 'issued')
                            <span class="status-pill status-pill--success">Выдан</span>
                        @elseif($loan->status == 'pending')
                            <span class="status-pill status-pill--warning">Ожидает</span>
                        @else
                            <span class="status-pill status-pill--error">Отклонён</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('manager.loans.show', $loan) }}" class="btn btn--ghost btn--sm">Просмотр</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $loans->links() }}
</div>
@endsection