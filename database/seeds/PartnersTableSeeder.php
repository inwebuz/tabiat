<?php

use App\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PartnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::create([
            'name' => '01',
            'image' => 'partners/01.jpg',
        ]);
        Partner::create([
            'name' => '02',
            'image' => 'partners/02.jpg',
        ]);
        Partner::create([
            'name' => '03',
            'image' => 'partners/03.jpg',
        ]);
        Partner::create([
            'name' => '04',
            'image' => 'partners/04.jpg',
        ]);
        Partner::create([
            'name' => '05',
            'image' => 'partners/05.jpg',
        ]);
        Partner::create([
            'name' => '06',
            'image' => 'partners/06.jpg',
        ]);
        Partner::create([
            'name' => '07',
            'image' => 'partners/07.jpg',
        ]);
        Partner::create([
            'name' => '08',
            'image' => 'partners/08.jpg',
        ]);
    }
}
