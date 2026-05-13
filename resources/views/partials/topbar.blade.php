<header class="topbar">
    <div style="display: flex; align-items: center; gap: 12px;">
        <button class="topbar__burger" onclick="toggleMobileMenu()">
            ☰
        </button>
        <div class="topbar__title">@yield('page_title', 'Альфа-Бизнес')</div>
    </div>
    
    <div class="topbar__right">
        <span class="topbar__tag">Внутренний процесс</span>
        
        <div class="topbar__user">
            <div class="topbar__user-avatar">
                {{ auth()->check() ? mb_substr(auth()->user()->name, 0, 1) : 'Г' }}
            </div>
            <div>
                <div class="topbar__user-name">{{ auth()->user()->name ?? 'Гость' }}</div>
                <div class="topbar__user-role">Корпоративный блок</div>
            </div>
        </div>
        
        @if(auth()->check())
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn--ghost btn--sm">Выйти</button>
        </form>
        @endif
    </div>
</header>

<script>
function toggleMobileMenu() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.style.display = sidebar.style.display === 'block' ? 'none' : 'block';
}
</script>