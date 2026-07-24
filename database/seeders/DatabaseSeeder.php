<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            // Compte d'administration + reglages du site.
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            // Contenu de demonstration.
            PostSeeder::class,
            DocumentSeeder::class,
            IndexSeeder::class,
            // En dernier : recuperation de calendriers via un service externe
            // (plus lent, necessite un acces reseau).
            CalendarSeeder::class,
        ]);
    }
}
