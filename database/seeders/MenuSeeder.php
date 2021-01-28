<?php

namespace Database\Seeders;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class MenuSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->file = '/database/seeds/menus.xlsx';
        $this->tablename = 'menus';
        $this->truncate = false;
        parent::run();
    }
}
