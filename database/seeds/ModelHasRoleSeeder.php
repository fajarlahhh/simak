<?php

use App\Pengguna;
use Illuminate\Database\Seeder;

class ModelHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $pengguna = Pengguna::findOrFail('admin');
        $pengguna->assignRole('super-admin');
    }
}
