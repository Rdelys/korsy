@extends('admin.layouts.guest')

@section('title', 'Vérification du code')

@section('content')
    <div class="text-center mb-6">
        <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary-fixed text-primary-container mb-3">
            <span class="material-symbols-outlined" style="font-size:30px" aria-hidden="true">mark_email_read</span>
        </span>
        <h1 class="text-xl font-bold text-primary mb-1">Vérifiez votre boîte mail</h1>
        <p class="text-sm text-on-surface-variant">
            Un code à 6 chiffres a été envoyé à <strong class="text-primary">{{ $maskedEmail }}</strong>.
            Il est valable 10 minutes.
        </p>
    </div>

    @if (session('status'))
        <div role="status" class="text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2.5 mb-4">{{ session('status') }}</div>
    @endif

    @error('code')
        <div role="alert" class="flex items-start gap-2 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg px-3 py-2.5 mb-4">
            <span class="material-symbols-outlined icon-fill shrink-0" aria-hidden="true">error</span>
            <span>{{ $message }}</span>
        </div>
    @enderror

    <form method="POST" action="{{ route('admin.otp.verify') }}" class="space-y-4">
        @csrf
        <label for="code" class="sr-only">Code de vérification</label>
        <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6"
               autocomplete="one-time-code" autofocus required placeholder="••••••"
               class="w-full max-w-[220px] mx-auto block text-center text-2xl font-bold tracking-[0.5em] py-3 rounded-lg border-outline-variant focus:border-primary-container focus:ring-primary-container text-primary">

        <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-lg bg-primary-container hover:bg-primary text-white text-sm font-bold transition-colors">
            <span>Vérifier et se connecter</span>
            <span class="material-symbols-outlined" aria-hidden="true">check</span>
        </button>
    </form>

    <div class="flex items-center justify-center gap-4 mt-5 text-sm">
        <form method="POST" action="{{ route('admin.otp.resend') }}">
            @csrf
            <button type="submit" class="text-primary font-bold hover:underline">Renvoyer le code</button>
        </form>
        <span class="text-outline-variant" aria-hidden="true">•</span>
        <a href="{{ route('admin.otp.cancel') }}" class="text-on-surface-variant hover:text-secondary">Annuler</a>
    </div>
@endsection