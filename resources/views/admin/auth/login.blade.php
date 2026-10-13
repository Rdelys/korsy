@extends('admin.layouts.guest')

@section('title', 'Connexion administrateur')

@section('content')
    <h1 class="text-xl font-bold text-primary text-center mb-1">Connexion</h1>
    <p class="text-sm text-on-surface-variant text-center mb-6">Accès réservé. Un code de vérification sera envoyé par e-mail.</p>

    @if (session('status'))
        <div role="status" class="text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2.5 mb-4">{{ session('status') }}</div>
    @endif

    @error('email')
        <div role="alert" class="flex items-start gap-2 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg px-3 py-2.5 mb-4">
            <span class="material-symbols-outlined icon-fill shrink-0" aria-hidden="true">error</span>
            <span>{{ $message }}</span>
        </div>
    @enderror
    @error('password')
        <div role="alert" class="text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg px-3 py-2.5 mb-4">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-primary mb-1">Adresse e-mail</label>
            <input id="email" name="email" type="text" inputmode="email" autocomplete="username" required autofocus
                   value="{{ old('email') }}"
                   class="w-full rounded-lg border-outline-variant focus:border-primary-container focus:ring-primary-container text-sm">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-primary mb-1">Mot de passe</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="w-full rounded-lg border-outline-variant focus:border-primary-container focus:ring-primary-container text-sm">
        </div>

        <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-lg bg-secondary hover:bg-secondary-container text-white text-sm font-bold transition-colors">
            <span>Recevoir mon code</span>
            <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
        </button>
    </form>
@endsection