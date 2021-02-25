<?php

use App\Banner;
use Illuminate\Database\Seeder;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // slide
        $banner = Banner::create([
            'name' => '01',
            'description_top' => 'Семяна и удобрения',
            'description' => 'для сельского хозяйства',
            'button_text' => 'Скачать каталог',
            'type' => 'slide',
            'image' => 'banners/01.jpg',
            'url' => '#',
            'status' => '1',
        ]);
            $banner = $banner->translate('uz');
            $banner->description_top = 'Urugʻlar va oʻgʻitlar';
            $banner->description = 'qishloq xoʻjaligi uchun';
            $banner->button_text = 'Katalogni yuklab oling';
            $banner->save();

        // Banner::create([
        //     'name' => '02',
        //     'description_top' => '2 Новая коллекция',
        //     'description' => 'Заглядывайте к нам чаще! Вы непременно найдете что-то интересное',
        //     'button_text' => 'Подробнее',
        //     'type' => 'slide',
        //     'image' => 'banners/02.jpg',
        //     'url' => '#',
        //     'status' => '1',
        // ]);

        // sidebar
        Banner::create([
            'name' => '01',
            'description_top' => '',
            'description' => '01',
            'button_text' => '',
            'type' => 'sidebar_1',
            'image' => 'banners/sidebar-01.jpg',
            'url' => '#',
            'status' => '1',
        ]);


        // home
        // Banner::create([
        //     'name' => '01',
        //     'description' => '',
        //     'button_text' => '',
        //     'type' => 'home_1',
        //     'image' => 'banners/home_01.jpg',
        //     'url' => '#',
        //     'status' => '1',
        // ]);
        // Banner::create([
        //     'name' => '02',
        //     'description' => '',
        //     'button_text' => '',
        //     'type' => 'home_2',
        //     'image' => 'banners/home_02.jpg',
        //     'url' => '#',
        //     'status' => '1',
        // ]);

        // middle
        // Banner::create([
        //     'name' => 'Middle 01',
        //     'description_top' => 'Для девочек',
        //     'description' => 'Акции и скидки',
        //     'description_bottom' => '',
        //     'button_text' => 'Смотреть все',
        //     'type' => 'middle_1',
        //     'image' => 'banners/middle-01.jpg',
        //     'url' => '#',
        //     'status' => '1',
        // ]);
        // Banner::create([
        //     'name' => 'Middle 02',
        //     'description_top' => '',
        //     'description' => '',
        //     'description_bottom' => '',
        //     'button_text' => '',
        //     'type' => 'middle_2',
        //     'image' => 'banners/middle-02.jpg',
        //     'url' => '#',
        //     'status' => '1',
        // ]);
    }
}
