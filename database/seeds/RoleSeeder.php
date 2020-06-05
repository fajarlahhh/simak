<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $level = array('super-admin', 'supervisor', 'user', 'guest');
        $i = 1;
        foreach ($level as $lvl) {
            if(Role::where('name', $lvl)->count() == 0)
                Role::create(['name' => $lvl]);
            $i++;
        }
    }
}
