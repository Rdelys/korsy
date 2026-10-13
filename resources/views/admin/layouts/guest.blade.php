<!DOCTYPE html>
<html lang="fr">
<head>
    @include('admin.partials.head')
</head>
<body class="font-sans text-on-surface antialiased min-h-screen bg-gradient-to-br from-primary-deep via-primary to-primary-container flex items-center justify-center p-4">

    <main class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-container via-secondary to-primary-container" aria-hidden="true"></div>

        <div class="p-8">
            <div class="flex flex-col items-center text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Tafely Kôrsy" class="h-10 w-auto mb-4">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-primary bg-primary-fixed px-3 py-1 rounded-full">
                    <span class="material-symbols-outlined icon-fill" style="font-size:14px" aria-hidden="true">lock</span>
                    Espace administrateur
                </span>
            </div>

            @yield('content')
        </div>
    </main>

</body>
</html>