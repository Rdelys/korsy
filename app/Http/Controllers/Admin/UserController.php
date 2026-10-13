<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginEvent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $role = $request->query('role');
        $status = $request->query('status');

        $users = User::query()
            ->when($search !== '', function ($q) use ($search) {
                $like = '%'.addcslashes($search, '%_\\').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like));
            })
            ->when(in_array($role, [User::ROLE_CLIENT, User::ROLE_VENDEUR], true), fn ($q) => $q->where('role', $role))
            ->when($status === 'pending_profile', fn ($q) => $q->whereNull('profile_verified_at'))
            ->when($status === 'pending_phone', fn ($q) => $q->whereNull('phone_verified_at'))
            ->when($status === 'verified', fn ($q) => $q->whereNotNull('profile_verified_at')->whereNotNull('phone_verified_at'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => ['q' => $search, 'role' => $role, 'status' => $status],
            'total' => User::count(),
        ]);
    }

    /** Valide (ou retire la validation du) profil. */
    public function toggleProfile(User $user): RedirectResponse
    {
        $this->toggle($user, 'profile_verified_at');

        return back()->with('status', $user->profile_verified_at
            ? "Profil de {$user->name} validé."
            : "Validation du profil de {$user->name} retirée.");
    }

    /** Valide (ou retire la validation du) numéro Mobile Money. */
    public function togglePhone(User $user): RedirectResponse
    {
        $this->toggle($user, 'phone_verified_at');

        return back()->with('status', $user->phone_verified_at
            ? "Numéro de {$user->name} validé."
            : "Validation du numéro de {$user->name} retirée.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $name = $user->name;

        DB::transaction(function () use ($user) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $user->loginOtps()->delete();
            $user->trustedDevices()->delete();
            LoginEvent::where('user_id', $user->id)->delete();

            $user->delete();
        });

        return back()->with('status', "Le compte de {$name} a été supprimé.");
    }

    private function toggle(User $user, string $column): void
    {
        // On n'altère pas « Dernière modification » côté utilisateur.
        $user->timestamps = false;
        $user->forceFill([$column => $user->{$column} ? null : now()])->save();
    }
}