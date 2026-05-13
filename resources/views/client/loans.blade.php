@extends('layouts.app')

@section('title', 'Мои кредиты')

@section('content')
@php
    $user = auth()->user();
    $client = \App\Models\Client::where('user_id', $user->id)->first();
    $loans = $client ? $client->loanApplications()->with('creditProduct')->orderBy('created_at', 'desc')->get() : collect();
@endphp

<div class="page-header">
    <div class="page-header__title">💳 Мои кредиты</div>
    <div class="page-header__subtitle">
        @if($client)
            {{ $client->short_name }} • {{ $loans->count() }} кредитов
        @endif
    </div>
</div>

@if($loans->count() > 0)
    @foreach($loans as $loan)
    @php
        $issueDate = $loan->issue_date;
        $monthsPassed = $issueDate->diffInMonths(now());
        $totalMonths = 12;
        $monthlyPayment = round($loan->amount / $totalMonths);
        $remaining = $loan->amount - ($monthlyPayment * min($monthsPassed, $totalMonths));
        $remaining = max(0, $remaining);
        $progress = $loan->amount > 0 ? round((($loan->amount - $remaining) / $loan->amount) * 100) : 0;
        $nextPaymentDate = now()->addMonth()->startOfMonth();
        $daysUntilPayment = now()->diffInDays($nextPaymentDate);
        $isOverdue = $monthsPassed > 0 && now()->day > 15;
    @endphp
    
    <div class="card" style="margin-bottom: 16px; position: relative; overflow: hidden;">
        <!-- Статусная полоса сверху -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; 
            @if($remaining <= 0) background: #16A34A;
            @elseif($isOverdue) background: #DC2626;
            @else background: #0F5ECC;
            @endif">
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px; margin-top: 8px;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span class="loan-tag">{{ $loan->creditProduct->name ?? 'Кредит' }}</span>
                    <span style="padding: 4px 8px; border-radius: 999px; font-size: 11px; 
                        @if($remaining <= 0) background: #DCFCE7; color: #166534;
                        @elseif($isOverdue) background: #FEE2E2; color: #991B1B;
                        @else background: #F0F9FF; color: #0369A1;
                        @endif">
                        @if($remaining <= 0) ✅ Погашен
                        @elseif($isOverdue) ⚠️ Просрочка
                        @else 🔵 Активен
                        @endif
                    </span>
                </div>
                <div style="font-size: 13px; color: var(--text-muted);">
                    Выдан: {{ $loan->issue_date->format('d.m.Y') }} • Ставка: {{ $loan->creditProduct->base_rate ?? '—' }}%
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 11px; color: var(--text-muted);">Остаток</div>
                <div class="text-mono text-strong" style="font-size: 18px;">
                    {{ number_format($remaining, 0, ',', ' ') }} ₽
                </div>
            </div>
        </div>
        
        <!-- Прогресс-бар -->
        <div style="margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-bottom: 4px;">
                <span>Погашено {{ $progress }}%</span>
                <span>{{ number_format($loan->amount, 0, ',', ' ') }} ₽</span>
            </div>
            <div style="height: 8px; background: #E5E7EB; border-radius: 999px; overflow: hidden;">
                <div style="height: 100%; width: {{ $progress }}%; 
                    @if($remaining <= 0) background: #16A34A;
                    @elseif($isOverdue) background: #DC2626;
                    @else background: linear-gradient(90deg, #0F5ECC, #38BDF8);
                    @endif 
                    border-radius: 999px; transition: width 0.3s;">
                </div>
            </div>
        </div>
        
        <!-- Детали платежа -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; padding: 16px; background: #F9FAFB; border-radius: 12px;">
            <div style="text-align: center;">
                <div style="font-size: 11px; color: var(--text-muted);">Ежемесячный платёж</div>
                <div class="text-mono text-strong" style="font-size: 16px; color: #0F5ECC;">
                    {{ number_format($monthlyPayment, 0, ',', ' ') }} ₽
                </div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 11px; color: var(--text-muted);">Следующий платёж</div>
                <div class="text-mono text-strong" style="font-size: 16px; 
                    @if($daysUntilPayment <= 5 && $remaining > 0) color: #DC2626; 
                    @else color: var(--text-main); 
                    @endif">
                    {{ $nextPaymentDate->format('d.m.Y') }}
                </div>
                @if($remaining > 0)
                    <div style="font-size: 10px; color: {{ $daysUntilPayment <= 5 ? '#DC2626' : '#16A34A' }};">
                        {{ $daysUntilPayment }} дн.
                    </div>
                @endif
            </div>
            <div style="text-align: center;">
                <div style="font-size: 11px; color: var(--text-muted);">Выплачено</div>
                <div class="text-mono text-strong" style="font-size: 16px; color: #16A34A;">
                    {{ number_format($loan->amount - $remaining, 0, ',', ' ') }} ₽
                </div>
            </div>
        </div>
        
        <!-- Кнопка оплаты -->
        @if($remaining > 0)
        <div style="margin-top: 12px; display: flex; gap: 8px; justify-content: flex-end;">
            <button onclick="showPaymentModal({{ $loan->id }}, {{ $monthlyPayment }})" 
                    class="btn btn--primary" style="padding: 10px 24px;">
                💳 Оплатить {{ number_format($monthlyPayment, 0, ',', ' ') }} ₽
            </button>
        </div>
        @endif
    </div>
    @endforeach
@else
    <div class="card" style="text-align: center; padding: 60px 40px;">
        <div style="font-size: 64px; margin-bottom: 16px;">💳</div>
        <h2 style="margin-bottom: 8px;">Нет активных кредитов</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px;">Оформите кредит для развития вашего бизнеса</p>
        <a href="{{ route('loans.create') }}" class="btn btn--primary">➕ Оформить кредит</a>
    </div>
@endif

<!-- Модальное окно оплаты -->
<div id="paymentModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 20px; padding: 32px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="font-size: 48px;">✅</div>
            <h3 style="margin: 8px 0;">Подтверждение платежа</h3>
            <p style="color: var(--text-muted); font-size: 14px;">Ежемесячный платёж по кредиту</p>
        </div>
        
        <div style="background: #F9FAFB; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 20px;">
            <div style="font-size: 28px; font-weight: 700; color: #0F5ECC;" id="paymentAmount">0 ₽</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Средства спишутся с расчётного счёта</div>
        </div>
        
        <div style="display: flex; gap: 8px;">
            <button onclick="processPayment()" class="btn btn--primary" style="flex: 1;">💳 Оплатить</button>
            <button onclick="closePaymentModal()" class="btn btn--ghost" style="flex: 1;">Отмена</button>
        </div>
    </div>
</div>

<div id="paymentSuccess" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1001; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 20px; padding: 32px; text-align: center; width: 100%; max-width: 400px;">
        <div style="font-size: 64px;">🎉</div>
        <h3>Платёж выполнен!</h3>
        <p style="color: var(--text-muted);">Квитанция отправлена на email</p>
        <button onclick="location.reload()" class="btn btn--primary" style="margin-top: 16px;">OK</button>
    </div>
</div>

<script>
let currentPaymentAmount = 0;

function showPaymentModal(loanId, amount) {
    currentPaymentAmount = amount;
    document.getElementById('paymentAmount').textContent = 
        new Intl.NumberFormat('ru-RU').format(amount) + ' ₽';
    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

function processPayment() {
    document.getElementById('paymentModal').style.display = 'none';
    document.getElementById('paymentSuccess').style.display = 'flex';
    
    setTimeout(() => {
        document.getElementById('paymentSuccess').style.display = 'none';
    }, 2000);
}

// Закрытие по клику вне модала
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) closePaymentModal();
});
</script>
@endsection