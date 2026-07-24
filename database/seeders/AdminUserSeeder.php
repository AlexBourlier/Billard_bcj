<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Compte administrateur de demonstration pour l'administration OpenAdmin.
 *
 * Identifiants (evaluation locale uniquement) : admin / password.
 * A supprimer ou changer avant toute mise en production.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('admin_users')->where('username', 'admin')->exists()) {
            return;
        }

        $userId = DB::table('admin_users')->insertGetId([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'name' => 'Administrateur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Rattache au role « Administrateur du site » (admin_site).
        $roleId = DB::table('admin_roles')->where('slug', 'admin_site')->value('id');
        if ($roleId !== null) {
            DB::table('admin_role_users')->insert([
                'role_id' => $roleId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
