<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BidangSeeder::class,
            SalamSeeder::class,
            KopSuratSeeder::class,
            JabatanSeeder::class,
            PenggunaSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            ModelHasRoleSeeder::class,
        ]);
    }
}
