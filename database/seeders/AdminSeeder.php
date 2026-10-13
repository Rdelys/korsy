<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = config('admin.initial_password');

        if (! $password) {
            $this->command?->error('Définissez ADMIN_INITIAL_PASSWORD dans .env avant de lancer ce seeder.');

            return;
        }

        Admin::updateOrCreate(
            ['email' => mb_strtolower(config('admin.email'))],
            ['name' => 'Administrateur Tafely Kôrsy', 'password' => $password],
        );

        $this->command?->info('Compte administrateur prêt : '.config('admin.email'));
    }
}