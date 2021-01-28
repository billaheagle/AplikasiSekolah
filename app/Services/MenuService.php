<?php

namespace App\Services;

use App\Models\Menu;
use Spatie\Permission\Models\Permission;

class MenuService {
    private $standard_permission = array('-access', '-create', '-update', '-delete', '-show');

    function integrateMenuPermission($parent_id, $single_menu, $model_name) {
        if ($parent_id != "" || $single_menu != "") {
            foreach ($this->standard_permission as $permission) {
                Permission::create([
                    'name' => $model_name . $permission,
                    'guard_name' => 'web'
                ]);
            }
        } else {
            Permission::create([
                'name' => $model_name . $this->standard_permission[0],
                'guard_name' => 'web'
            ]);
        }
    }

    function updateMenuPermission(Menu $menu, $old_model_name, $model_name) {
        $permissions = Permission::where('name', 'LIKE', $old_model_name . '%')->get();
        foreach ($permissions as $permission) {
            $exp = explode("-", $permission->name);
            $pop = '-' . array_pop($exp);

            $temp = Permission::where('name', $old_model_name . $pop)->first();
            if($temp) {
                $temp->name = $model_name . $pop;
                $temp->save();
            }
        }

        if(! $menu->children->isEmpty()) {
            $childerns = Menu::where('parent_id', $menu->id)->get();
            foreach ($childerns as $child) {
                $child->url = $menu->url . '/' . $child->model_name;
                $child->save();
            }
        }
    }

    function revokeMenuPermission(Menu $menu) {
        $permissions = Permission::where('name', 'LIKE', $menu->model_name . '%')->get();
        foreach ($permissions as $permission) {
            $exp = explode("-", $permission->name);
            $pop = '-' . array_pop($exp);
            $temp = Permission::where('name', $menu->model_name . $pop)->first();
            if($temp) $temp->delete();
        }
    }
}
