@extends('layouts.app')

@section('title', 'Заявки на регистрацию')

@section('content')
<div class="page-header">
    <div class="page-header__title">Заявки на регистрацию компаний</div>
    <div class="page-header__subtitle">Одобрение новых клиентов банка</div>
</div>

<div class="card">
    <div style="margin-bottom: 16px;">
        <a href="?status=pending" class="btn btn--ghost" style="margin-right: 8px;">Ожидают</a>
        <a href="?status=approved" class="btn btn--ghost" style="margin-right: 8px;">Одобрены</a>
        <a href="?status=rejected" class="btn btn--ghost">Отклонены</a>
        <a href="?" class="btn btn--ghost">Все</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Компания</th>
                    <th>Контактное лицо</th>
                    <th>Email</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                <tr>
                    <td>#{{ $reg->id }}</td>
                    <td>{{ $reg->company_short_name }}</td>
                    <td>{{ $reg->contact_person }}</td>
                    <td>{{ $reg->email }}</td>
                    <td>
                        @if($reg->status == 'pending')
                            <span style="background: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 999px; font-size: 12px;">Ожидает</span>
                        @elseif($reg->status == 'approved')
                            <span style="background: #DCFCE7; color: #166534; padding: 4px 8px; border-radius: 999px; font-size: 12px;">Одобрена</span>
                        @else
                            <span style="background: #FEE2E2; color: #991B1B; padding: 4px 8px; border-radius: 999px; font-size: 12px;">Отклонена</span>
                        @endif
                    </td>
                    <td>{{ $reg->created_at->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('manager.registrations.show', $reg) }}" style="color: #0F5ECC;">Просмотр</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $registrations->links() }}
</div>
@endsection