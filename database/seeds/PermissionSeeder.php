<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (config('sidebar.menu') as $key => $menu) {
            if (!empty($menu['sub_menu'])) {
                if(Permission::where('name', strtolower($menu['id']))->count() == 0)
                    Permission::create(['name' => strtolower($menu['id'])]);
                foreach ($menu['sub_menu'] as $key => $sub) {
                    if(Permission::where('name', $sub['id'])->count() == 0)
                        Permission::create(['name' => $sub['id']]);
                }
            }else{
                if(Permission::where('name', $menu['id'])->count() == 0)
                    Permission::create(['name' => $menu['id']]);
            }
        }
    }
}
