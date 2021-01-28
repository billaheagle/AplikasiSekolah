<?php

namespace Database\Seeders;

use Spatie\Permission\PermissionRegistrar;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class PermissionSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->file = '/database/seeds/permissions.xlsx';
        $this->tablename = 'permissions';
        $this->truncate = false;
        parent::run();
    }
}
