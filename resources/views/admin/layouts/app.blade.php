<!DOCTYPE html>
<html lang="fr">
<head>
    @include('admin.partials.head')
    @stack('head')
</head>
<body class="font-sans text-on-surface antialiased bg-background">
@php
    $unseen = \App\Models\AdminLoginAttempt::unseenCount();

    $nav = [
        ['route' => 'admin.dashboard',   'match' => 'admin.dashboard',   'label' => 'Dashboard',    'icon' => 'dashboard'],
        ['route' => 'admin.users.index', 'match' => 'admin.users.*',     'label' => 'Utilisateurs', 'icon' => 'group'],
        ['route' => 'admin.payments',    'match' => 'admin.payments',    'label' => 'Paiements',    'icon' => 'payments'],
        ['route' => 'admin.history',     'match' => 'admin.history',     'label' => 'Historique',   'icon' => 'history'],
        ['route' => 'admin.security',    'match' => 'admin.security',    'label' => 'Sécurité',     'icon' => 'shield', 'badge' => $unseen],
    ];
@endphp

<div class="min-h-screen lg:flex">

    {{-- Fond sombre du tiroir (mobile) --}}
    <div id="sidebar-overlay" class="hidden fixed inset-0 z-30 bg-primary-deep/60 lg:hidden"></div>

    {{-- ============ BARRE LATÉRALE GAUCHE ============ --}}
    <aside id="admin-sidebar"
           class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transition-transform duration-200 bg-primary-deep text-white flex flex-col lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:shrink-0">

        <div class="h-16 px-5 flex items-center border-b border-white/10 shrink-0">
            <span class="inline-flex bg-white rounded-lg px-2.5 py-1.5">
                <img src="{{ asset('images/logo.png') }}" alt="Tafely Kôrsy" class="h-7 w-auto">
            </span>
            <span class="ml-3 text-xs font-bold uppercase tracking-wider text-blue-200">Admin</span>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1" aria-label="Menu administrateur">
            @foreach ($nav as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   @if ($active) aria-current="page" @endif
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors
                          {{ $active ? 'bg-white/15 text-white' : 'text-blue-100/80 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined {{ $active ? 'icon-fill' : '' }}" aria-hidden="true">{{ $item['icon'] }}</span>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if (! empty($item['badge']))
                        <span class="min-w-[1.4rem] h-6 px-1.5 inline-flex items-center justify-center rounded-full bg-secondary text-white text-xs font-bold animate-pulse"
                              title="{{ $item['badge'] }} tentative(s) de connexion suspecte(s) non consultée(s)">{{ $item['badge'] > 99 ? '99+' : $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="p-3 border-t border-white/10 shrink-0">
            <a href="{{ url('/') }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-blue-100/80 hover:bg-white/10 hover:text-white transition-colors">
                <span class="material-symbols-outlined" aria-hidden="true">open_in_new</span>
                <span>Voir le site</span>
            </a>
        </div>
    </aside>

    {{-- ============ COLONNE PRINCIPALE ============ --}}
    <div class="flex-1 min-w-0 flex flex-col">

        {{-- Barre de navigation --}}
        <header class="sticky top-0 z-20 h-16 bg-white border-b border-outline-variant/50 flex items-center justify-between gap-3 px-4 sm:px-6">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" id="sidebar-toggle" aria-label="Ouvrir le menu" aria-controls="admin-sidebar"
                        class="lg:hidden p-2 -ml-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low">
                    <span class="material-symbols-outlined" aria-hidden="true">menu</span>
                </button>
                <h1 class="text-lg font-bold text-primary truncate">@yield('heading', 'Administration')</h1>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <a href="{{ route('admin.security') }}" aria-label="Sécurité"
                   class="relative p-2 rounded-lg text-on-surface-variant hover:bg-surface-container-low">
                    <span class="material-symbols-outlined" aria-hidden="true">notifications</span>
                    @if ($unseen)
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-secondary ring-2 ring-white"></span>
                    @endif
                </a>

                <span class="hidden sm:inline text-sm text-on-surface-variant truncate max-w-[14rem]">{{ auth('admin')->user()->email }}</span>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-outline-variant text-sm font-semibold text-secondary hover:bg-secondary-fixed/40 transition-colors">
                        <span class="material-symbols-outlined" aria-hidden="true">logout</span>
                        <span class="hidden sm:inline">Déconnexion</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 space-y-6">
            @if (session('status'))
                <div role="status" class="flex items-start gap-2 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
                    <span class="material-symbols-outlined icon-fill" aria-hidden="true">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        var sidebar = document.getElementById('admin-sidebar');
        var overlay = document.getElementById('sidebar-overlay');
        var btn = document.getElementById('sidebar-toggle');

        function set(open) {
            sidebar.classList.toggle('-translate-x-full', !open);
            overlay.classList.toggle('hidden', !open);
        }
        btn.addEventListener('click', function () { set(sidebar.classList.contains('-translate-x-full')); });
        overlay.addEventListener('click', function () { set(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') set(false); });
    })();
</script>
@stack('scripts')
</body>
</html>