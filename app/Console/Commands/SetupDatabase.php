<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SetupDatabase extends Command
{
    protected $signature = 'db:setup';

    protected $description = 'Crée la base de données si elle n’existe pas';

    public function handle(): int
    {
        $database = env('DB_DATABASE');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', 3306);
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        try {
            // Connexion MySQL sans sélectionner la base
            Config::set('database.connections.mysql_server', [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $port,
                'database' => null,
                'username' => $username,
                'password' => $password,
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]);

            DB::connection('mysql_server')->statement(
                "CREATE DATABASE IF NOT EXISTS `$database` 
                 CHARACTER SET utf8mb4 
                 COLLATE utf8mb4_unicode_ci"
            );

            $this->info("Base de données `$database` prête.");

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->error("Impossible de créer la base :");
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}