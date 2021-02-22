<?php

use App\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('brands')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        factory(Brand::class)->create([
            'name' => '1',
            'slug' => Str::slug('1'),
            'image' => 'brands/logo1.png',
        ]);

        factory(Brand::class)->create([
            'name' => '2',
            'slug' => Str::slug('2'),
            'image' => 'brands/logo2.png',
        ]);

        factory(Brand::class)->create([
            'name' => '3',
            'slug' => Str::slug('3'),
            'image' => 'brands/logo3.png',
        ]);

        factory(Brand::class)->create([
            'name' => '4',
            'slug' => Str::slug('4'),
            'image' => 'brands/logo4.png',
        ]);

        factory(Brand::class)->create([
            'name' => '5',
            'slug' => Str::slug('5'),
            'image' => 'brands/logo5.png',
        ]);

        factory(Brand::class)->create([
            'name' => '6',
            'slug' => Str::slug('6'),
            'image' => 'brands/logo6.png',
        ]);
    }
}
