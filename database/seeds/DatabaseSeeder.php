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
            TembusanSeeder::class,
            PenomoranSeeder::class,
            GambarSeeder::class,
            BidangSeeder::class,
            SalamSeeder::class,
            KopSuratSeeder::class,
            JabatanSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            PenggunaSeeder::class,
            ModelHasRoleSeeder::class,
        ]);
    }
}
