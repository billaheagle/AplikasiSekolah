<?php

namespace Database\Seeders;

use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class RolePermissionSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/seeds/role_has_permissions.xlsx';
        $this->tablename = 'role_has_permissions';
        $this->truncate = false;
        parent::run();
    }
}
