<?php

use App\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = factory(Page::class)->create([
            'name' => 'Главная',
            'slug' => 'home',
            'order' => 1,
            'show_in' => 2,
        ]);
        $page = $page->translate('uz');
        $page->name = 'Bosh sahifa';
        $page->save();

        $page = factory(Page::class)->create([
            'name' => 'Контакты',
            'slug' => 'contacts',
            'order' => 1000,
            'show_in' => 3,
        ]);
        $page = $page->translate('uz');
        $page->name = 'Aloqa';
        $page->save();

        $page = factory(Page::class)->create([
            'name' => 'О компании',
            'slug' => 'about',
            'order' => 10,
            'show_in' => 3,
        ]);
        $page = $page->translate('uz');
        $page->name = 'Kompaniya haqida';
        $page->save();

        $page = factory(Page::class)->create([
            'name' => 'Каталог',
            'slug' => 'catalogue',
            'order' => 20,
            'show_in' => 0,
        ]);
        $page = $page->translate('uz');
        $page->name = 'Katalog';
        $page->save();

        $page = factory(Page::class)->create([
            'name' => 'Новости',
            'slug' => 'news',
            'order' => 100,
            'show_in' => 3,
        ]);
        $page = $page->translate('uz');
        $page->name = 'Yangiliklar';
        $page->save();

        // $page = factory(Page::class)->create([
        //     'name' => 'Бренды',
        //     'slug' => 'brands',
        //     'order' => 70,
        //     'show_in' => 1,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'Brendlar';
        // $page->save();


        // $page = factory(Page::class)->create([
        //     'name' => 'Частые вопросы',
        //     'slug' => Str::slug('Частые вопросы'),
        //     'order' => 70,
        //     'show_in' => 2,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'FAQ';
        // $page->save();

        // $page = factory(Page::class)->create([
        //     'name' => 'Публичная офферта',
        //     'slug' => Str::slug('Публичная офферта'),
        //     'order' => 80,
        //     'show_in' => 2,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'Oferta';
        // $page->save();

        // $page = factory(Page::class)->create([
        //     'name' => 'Условия доставки',
        //     'slug' => Str::slug('Условия доставки'),
        //     'order' => 90,
        //     'show_in' => 2,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'Yetkazib berish';
        // $page->save();

        // $page = factory(Page::class)->create([
        //     'name' => 'Условия оплаты',
        //     'slug' => Str::slug('Условия оплаты'),
        //     'order' => 100,
        //     'show_in' => 2,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'To‘lov shartlari';
        // $page->save();

        // $page = factory(Page::class)->create([
        //     'name' => 'Магазины',
        //     'slug' => 'shops',
        //     'order' => 110,
        //     'show_in' => 2,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'Magazinlar';
        // $page->save();

        // $page = factory(Page::class)->create([
        //     'name' => 'Featured',
        //     'slug' => 'featured',
        //     'order' => 120,
        //     'show_in' => 1,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'Featured';
        // $page->save();

        // $page = factory(Page::class)->create([
        //     'name' => 'Sale',
        //     'slug' => 'sale',
        //     'order' => 130,
        //     'show_in' => 1,
        // ]);
        // $page = $page->translate('uz');
        // $page->name = 'Sale';
        // $page->save();

    }
}
