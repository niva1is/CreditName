<aside class="sidebar">
    <!-- Логотип -->
    <div class="sidebar__logo">
        <div class="sidebar__logo-mark">A</div>
        <div>
            <div class="sidebar__logo-title">Альфа-Бизнес</div>
            <div class="sidebar__logo-subtitle">Корпоративный блок</div>
        </div>
    </div>

    <!-- Основная навигация (для всех) -->
    <div class="nav-section">
        <div class="nav-section__title">Навигация</div>
        <ul class="nav">
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-link__icon">📊</span>
                    <span class="nav-link__label">Обзор кредитов</span>
                </a>
            </li>
            
            {{-- Реестр кредитов — только для сотрудников --}}
            @if(!auth()->check() || !auth()->user()->hasRole('client'))
            <li>
                <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}">
                    <span class="nav-link__icon">📑</span>
                    <span class="nav-link__label">Реестр кредитов</span>
                </a>
            </li>
            @endif
            
            {{-- Взять кредит — только для клиентов --}}
            @if(auth()->check() && auth()->user()->hasRole('client'))
            <li>
                <a href="{{ route('loans.create') }}" class="nav-link {{ request()->routeIs('loans.create') ? 'active' : '' }}">
                    <span class="nav-link__icon">💰</span>
                    <span class="nav-link__label">Взять кредит</span>
                </a>
            </li>
            @endif
        </ul>
    </div>

    {{-- Управление — для сотрудников --}}
    @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'credit_manager', 'supervisor']))
    <div class="nav-section">
        <div class="nav-section__title">Управление</div>
        <ul class="nav">
            {{-- Заявки на кредит --}}
            <li>
                <a href="{{ route('loans.create') }}" class="nav-link {{ request()->routeIs('loans.create') ? 'active' : '' }}">
                    <span class="nav-link__icon">📝</span>
                    <span class="nav-link__label">Заявки на кредит</span>
                </a>
            </li>
            
            {{-- Компании --}}
            <li>
                <a href="{{ route('companies.index') }}" class="nav-link {{ request()->routeIs('companies.*') || request()->routeIs('clients.*') ? 'active' : '' }}">
                    <span class="nav-link__icon">🏢</span>
                    <span class="nav-link__label">Компании</span>
                </a>
            </li>
            
            {{-- Заявки на регистрацию --}}
            <li>
                <a href="{{ route('manager.registrations.index') }}" class="nav-link {{ request()->routeIs('manager.registrations.*') ? 'active' : '' }}">
                    <span class="nav-link__icon">📋</span>
                    <span class="nav-link__label">Заявки на регистрацию</span>
                    @php
                        $pendingCount = App\Models\RegistrationRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="nav-link__badge">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
    @endif

    {{-- Личный кабинет клиента --}}
    @if(auth()->check() && auth()->user()->hasRole('client'))
    <div class="nav-section">
        <div class="nav-section__title">Личный кабинет</div>
        <ul class="nav">
            <li>
                <a href="{{ route('client.profile') }}" class="nav-link {{ request()->routeIs('client.profile') ? 'active' : '' }}">
                    <span class="nav-link__icon">👤</span>
                    <span class="nav-link__label">Мой профиль</span>
                </a>
            </li>
            <li>
                <a href="{{ route('client.loans') }}" class="nav-link {{ request()->routeIs('client.loans') ? 'active' : '' }}">
                    <span class="nav-link__icon">💳</span>
                    <span class="nav-link__label">Мои кредиты</span>
                </a>
            </li>
        </ul>
    </div>
    @endif

    <!-- Футер -->
    <div class="sidebar__footer">
        <div style="margin-bottom: 16px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color: #606981;">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <a href="tel:88001002424" style="color: #2f3441; font-size: 14px; font-weight: 500; text-decoration: none;">
                    8 (800) 100-24-24
                </a>
            </div>
            <div style="font-size: 11px; color: #606981; margin-left: 24px;">
                Бесплатный звонок по России
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <a href="https://yandex.ru/maps/57/khanty-mansiysk/chain/bank_vtb_atms/48064199351/?ll=69.027448%2C61.001735&sctx=ZAAAAAgCEAAaKAoSCSNNvAM8VFJAER0ewvhppE5AEhIJP28qUmFs1T8R%2BMH51LFKuT8iBgABAgMEBSgKOABAOUgBYjpyZWFycj1zY2hlbWVfTG9jYWwvR2VvdXBwZXIvQWR2ZXJ0cy9DdXN0b21NYXhhZHYvRW5hYmxlZD0xYjpyZWFycj1zY2hlbWVfTG9jYWwvR2VvdXBwZXIvQWR2ZXJ0cy9DdXN0b21NYXhhZHYvTWF4YWR2PTE1YkRyZWFycj1zY2hlbWVfTG9jYWwvR2VvdXBwZXIvQWR2ZXJ0cy9DdXN0b21NYXhhZHYvUmVnaW9uSWRzPVsxLDEwMTc0XWJAcmVhcnI9c2NoZW1lX0xvY2FsL0dlb3VwcGVyL0FkdmVydHMvTWF4YWR2VG9wTWl4L01heGFkdkZvck1peD0xMGoCcnWdAc3MzD2gAQCoAQC9Adx%2BRH%2FCAS6OveDSvASx5v3D3QH9xfbQaMnbrfLOBcXStJsGurXCi%2FwGyYrhjqAByK%2B1yrQGggJE0JHQsNC90Log0JLQotCRLCDQsdCw0L3QutC%2B0LzQsNGC0Ysg0LIg0YXQsNC90YLRiyDQvNCw0L3RgdC40LnRgdC60LWKAgCSAgI1N5oCDGRlc2t0b3AtbWFwc6oCCzQ4MDY0MTk5MzUxsAIB&sll=69.027448%2C61.001735&sspn=0.124232%2C0.036997&z=13.43" style="display: flex; align-items: center; gap: 8px; color: #2f3441; font-size: 14px; text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color: #606981;">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                Отделения и банкоматы
            </a>
        </div>

        <div style="margin-bottom: 16px;">
            <a href="https://platacard.mx/en/about" style="display: flex; align-items: center; gap: 8px; color: #2f3441; font-size: 14px; text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color: #606981;">
                    <path d="M12 2L2 7v2h2v9H2v2h20v-2h-2V9h2V7l-10-5zm-1 8h2v2h-2v-2zm0 4h2v4h-2v-4z"/>
                </svg>
                О банке
            </a>
        </div>
    </div>
</aside>