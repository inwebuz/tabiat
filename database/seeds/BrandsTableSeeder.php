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
            'name' => 'AquaDabra',
            'slug' => Str::slug('AquaDabra'),
            'image' => 'brands/AquaDabra.jpg',
        ]);

        factory(Brand::class)->create([
            'name' => 'Aquabeads',
            'slug' => Str::slug('Aquabeads'),
            'image' => 'brands/Aquabeads.jpg',
        ]);

        factory(Brand::class)->create([
            'name' => 'Bioblo',
            'slug' => Str::slug('Bioblo'),
            'image' => 'brands/Bioblo.jpg',
        ]);

        factory(Brand::class)->create([
            'name' => 'Boomtrix',
            'slug' => Str::slug('Boomtrix'),
            'image' => 'brands/Boomtrix.jpg',
        ]);

        factory(Brand::class)->create([
            'name' => 'Bruder',
            'slug' => Str::slug('Bruder'),
            'image' => 'brands/Bruder.jpg',
        ]);

        factory(Brand::class)->create([
            'name' => 'Bruder 2',
            'slug' => Str::slug('Bruder 2'),
            'image' => 'brands/Bruder.jpg',
        ]);

        factory(Brand::class)->create([
            'name' => 'Bruder 3',
            'slug' => Str::slug('Bruder 3'),
            'image' => 'brands/Bruder.jpg',
        ]);
    }
}
