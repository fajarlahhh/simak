<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                if(DB::table('permissions')->where('name', strtolower($menu['id']))->count() == 0)
                    Permission::create(['name' => strtolower($menu['id'])]);
                foreach ($menu['sub_menu'] as $key => $sub) {
                    if(DB::table('permissions')->where('name', $sub['id'])->count() == 0)
                        Permission::create(['name' => $sub['id']]);
                }
            }else{
                if(DB::table('permissions')->where('name', $menu['id'])->count() == 0)
                    Permission::create(['name' => $menu['id']]);
            }
        }
    }
}
