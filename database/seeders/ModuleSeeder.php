<?php

namespace Database\Seeders;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $module = new Module();
        $module->name = "No Module";
        $module->save();
    }
}
