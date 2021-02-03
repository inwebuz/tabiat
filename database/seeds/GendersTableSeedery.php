<?php

use App\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('genders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Gender::create([
            'name' => 'Мужской',
        ]);

        Gender::create([
            'name' => 'Женский',
        ]);

        Gender::create([
            'name' => 'Мальчик',
        ]);

        Gender::create([
            'name' => 'Девочка',
        ]);

        Gender::create([
            'name' => 'Унисекс',
        ]);

    }
}
