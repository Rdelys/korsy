@extends('admin.layouts.app')

@section('title', 'Utilisateurs')
@section('heading', 'Utilisateurs')

@section('content')
@php
    $card = 'bg-white rounded-xl border border-outline-variant/50 shadow-sm';
    $btn  = 'inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border text-xs font-semibold transition-colors';
@endphp

<section class="{{ $card }} p-4 sm:p-5">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
        <div class="sm:col-span-5 relative">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-outline" aria-hidden="true">search</span>
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Nom, e-mail ou téléphone…"
                   class="w-full pl-10 rounded-lg border-outline-variant focus:border-primary-container focus:ring-primary-container text-sm">
        </div>

        <select name="role" class="sm:col-span-2 rounded-lg border-outline-variant focus:border-primary-container focus:ring-primary-container text-sm">
            <option value="">Tous les profils</option>
            <option value="client" @selected($filters['role'] === 'client')>Apprenants</option>
            <option value="vendeur" @selected($filters['role'] === 'vendeur')>Vendeurs</option>
        </select>

        <select name="status" class="sm:col-span-3 rounded-lg border-outline-variant focus:border-primary-container focus:ring-primary-container text-sm">
            <option value="">Tous les statuts</option>
            <option value="pending_profile" @selected($filters['status'] === 'pending_profile')>Profil à valider</option>
            <option value="pending_phone" @selected($filters['status'] === 'pending_phone')>Numéro à valider</option>
            <option value="verified" @selected($filters['status'] === 'verified')>Entièrement validés</option>
        </select>

        <div class="sm:col-span-2 flex gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-primary-container hover:bg-primary text-white text-sm font-bold px-3 py-2 transition-colors">Filtrer</button>
            @if ($filters['q'] || $filters['role'] || $filters['status'])
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-outline-variant text-sm font-semibold text-on-surface-variant px-3 py-2 hover:bg-surface-container-low" title="Réinitialiser">✕</a>
            @endif
        </div>
    </form>
</section>

<section class="{{ $card }} overflow-hidden">
    <div class="px-5 py-3 border-b border-outline-variant/40 flex items-center justify-between">
        <h2 class="text-sm font-bold text-primary">{{ number_format($users->total(), 0, ',', "\u{00A0}") }} résultat(s)
            <span class="font-normal text-on-surface-variant">sur {{ number_format($total, 0, ',', "\u{00A0}") }} utilisateur(s)</span>
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-surface-container-low text-xs uppercase tracking-wider text-on-surface-variant">
                <tr>
                    <th class="text-left font-bold px-5 py-3">Utilisateur</th>
                    <th class="text-left font-bold px-3 py-3">Profil</th>
                    <th class="text-left font-bold px-3 py-3">Téléphone</th>
                    <th class="text-left font-bold px-3 py-3">Validations</th>
                    <th class="text-left font-bold px-3 py-3 whitespace-nowrap">Inscrit le</th>
                    <th class="text-right font-bold px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/30">
                @forelse ($users as $u)
                    <tr class="hover:bg-surface-container-low/50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <x-user-avatar :user="$u" size="w-9 h-9" text="text-sm font-bold" />
                                <div class="min-w-0">
                                    <p class="font-semibold text-on-surface truncate max-w-[14rem]">{{ $u->name }}</p>
                                    <p class="text-xs text-on-surface-variant truncate max-w-[14rem]">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-3 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $u->isVendeur() ? 'bg-secondary-fixed text-secondary' : 'bg-primary-fixed text-primary' }}">
                                {{ $u->isVendeur() ? 'Vendeur' : 'Apprenant' }}
                            </span>
                        </td>

                        <td class="px-3 py-3 whitespace-nowrap text-on-surface">{{ $u->phone }}</td>

                        <td class="px-3 py-3">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $u->profile_verified_at ? 'text-emerald-700' : 'text-amber-700' }}">
                                    <span class="material-symbols-outlined icon-fill" style="font-size:15px" aria-hidden="true">{{ $u->profile_verified_at ? 'verified' : 'pending' }}</span>
                                    Profil {{ $u->profile_verified_at ? 'validé' : 'en attente' }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $u->phone_verified_at ? 'text-emerald-700' : 'text-amber-700' }}">
                                    <span class="material-symbols-outlined icon-fill" style="font-size:15px" aria-hidden="true">{{ $u->phone_verified_at ? 'check_circle' : 'pending' }}</span>
                                    Numéro {{ $u->phone_verified_at ? 'validé' : 'en attente' }}
                                </span>
                            </div>
                        </td>

                        <td class="px-3 py-3 whitespace-nowrap text-on-surface-variant">{{ $u->created_at->format('d/m/Y') }}</td>

                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <form method="POST" action="{{ route('admin.users.profile', $u) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="{{ $btn }} {{ $u->profile_verified_at ? 'border-outline-variant text-on-surface-variant hover:bg-surface-container-low' : 'border-emerald-300 text-emerald-700 hover:bg-emerald-50' }}">
                                        <span class="material-symbols-outlined" style="font-size:16px" aria-hidden="true">{{ $u->profile_verified_at ? 'remove_done' : 'verified' }}</span>
                                        {{ $u->profile_verified_at ? 'Retirer profil' : 'Valider profil' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.phone', $u) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="{{ $btn }} {{ $u->phone_verified_at ? 'border-outline-variant text-on-surface-variant hover:bg-surface-container-low' : 'border-primary-container/40 text-primary-container hover:bg-primary-fixed' }}">
                                        <span class="material-symbols-outlined" style="font-size:16px" aria-hidden="true">{{ $u->phone_verified_at ? 'phone_disabled' : 'phone_in_talk' }}</span>
                                        {{ $u->phone_verified_at ? 'Retirer n°' : 'Valider n°' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                      onsubmit="return confirm('Supprimer définitivement le compte de {{ e(addslashes($u->name)) }} ? Cette action est irréversible.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="{{ $btn }} border-red-300 text-red-700 hover:bg-red-50">
                                        <span class="material-symbols-outlined" style="font-size:16px" aria-hidden="true">delete</span>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-on-surface-variant">Aucun utilisateur ne correspond à ces critères.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="px-5 py-3 border-t border-outline-variant/40">{{ $users->links() }}</div>
    @endif
</section>
@endsection