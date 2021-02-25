<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // id = 1
        $category = factory(Category::class)->create([
            'name' => 'Удобрения',
            'slug' => Str::slug('Удобрения'),
            'image' => 'categories/01.jpg',
            'show_in' => 2,
        ]);
        $category = $category->translate('uz');
        $category->name = 'Oʻgʻitlar';
        $category->save();

            $category1 = factory(Category::class)->create([
                'parent_id' => $category->id,
                'name' => 'Минеральные удобрения',
                'slug' => Str::slug('Минеральные удобрения'),
                'show_in' => 2,
            ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Новалон',
                    'slug' => Str::slug('Новалон'),
                    'show_in' => 2,
                ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Hydroponica',
                    'slug' => Str::slug('Hydroponica'),
                    'show_in' => 2,
                ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Calmag',
                    'slug' => Str::slug('Calmag'),
                    'show_in' => 2,
                ]);

            $category1 = factory(Category::class)->create([
                'parent_id' => $category->id,
                'name' => 'Фосфорные удобрения',
                'slug' => Str::slug('Фосфорные удобрения'),
                'show_in' => 2,
            ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Новалон',
                    'slug' => Str::slug('Новалон'),
                    'show_in' => 2,
                ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Hydroponica',
                    'slug' => Str::slug('Hydroponica'),
                    'show_in' => 2,
                ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Calmag',
                    'slug' => Str::slug('Calmag'),
                    'show_in' => 2,
                ]);
                $category1 = factory(Category::class)->create([
                'parent_id' => $category->id,
                'name' => 'Калийные удобрения',
                'slug' => Str::slug('Калийные удобрения'),
                'show_in' => 2,
            ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Новалон',
                    'slug' => Str::slug('Новалон'),
                    'show_in' => 2,
                ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Hydroponica',
                    'slug' => Str::slug('Hydroponica'),
                    'show_in' => 2,
                ]);
                factory(Category::class)->create([
                    'parent_id' => $category1->id,
                    'name' => 'Calmag',
                    'slug' => Str::slug('Calmag'),
                    'show_in' => 2,
                ]);

        // id = 2
        $category = factory(Category::class)->create([
            'name' => 'Семяна',
            'slug' => Str::slug('Семяна'),
            'image' => 'categories/02.jpg',
            'show_in' => 2,
        ]);
        $category = $category->translate('uz');
        $category->name = 'Urugʻlar';
        $category->save();

//        factory(Category::class, 10)->create();
//        factory(Category::class, 5)->create([
//           'parent_id' => Category::inRandomOrder()->first()->id,
//        ]);
//        factory(Category::class, 6)->create([
//           'parent_id' => Category::inRandomOrder()->first()->id,
//        ]);
//        factory(Category::class, 8)->create([
//           'parent_id' => Category::inRandomOrder()->first()->id,
//        ]);
//        factory(Category::class, 5)->create([
//           'parent_id' => Category::inRandomOrder()->first()->id,
//        ]);
//        factory(Category::class, 6)->create([
//           'parent_id' => Category::inRandomOrder()->first()->id,
//        ]);

    }
}
