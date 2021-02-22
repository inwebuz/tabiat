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
            'body' => '<p class="sub-title fadeInUp wow" data-wow-delay=".2s" data-wow-duration=".5s">Миссия</p>
            <h2 class="fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">Ваш успех - это наша ценность!</h2>
            <p class="fadeInUp wow" data-wow-delay=".4s" data-wow-duration=".5s">OOO «GO’ZAL TABIAT» был создан в 2019 году для поддержки развития сельского хозяйства, в
                частности
                для поддержки тепличных хозяйств, обеспечения их высокотехнологичными семенами, удобрениями
                и
                тепличными системами под ключ от ведущих компаний мира. Компания занимается продажей семян,
                удобрений, биопрепаратов, пестицидов и теплиц. Мы были созданы с участием иностранного
                инвестора,
                который имеет огромный и многолетний опыт в сфере выращивания фруктово-овощных культур как с
                применением высокотехнологичных тепличных систем, так и открытых хозяйствах в нескольких
                странах с
                площадью около 1000 Га.</p>
            <p class="fadeInUp wow" data-wow-delay=".5s" data-wow-duration=".5s">OOO «GO’ZAL TABIAT», официальный представитель Монсанто (Seminis, DeRuiter), Doktor Tarsa,
                Buyskiy,
                Syngenta. <br> Продукция импортируется из Нидерландов, Турции, России, США, Шри-ланка и
                других стран.</p>',
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
            'body' => '<h2 class="fadeInUp wow" data-wow-delay=".3s" data-wow-duration=".5s">«GO’ZAL TABIAT»</h2>
            <p class="fadeInUp wow" data-wow-delay=".4s" data-wow-duration=".5s"><b>Компания была создана в 2019 году</b> для поддержки развития сельского хозяйства, в
                частности для поддержки тепличных хозяйств, обеспечения их высокотехнологичными <span>семенами</span>,
                удобрениями и тепличными системами под ключ от ведущих компаний мира.</p>
            <p class="fadeInUp wow" data-wow-delay=".5s" data-wow-duration=".5s"><b>Компания занимается продажей семян, </b><span>удобрений, </span>биопрепаратов, пестицидов
                и теплиц. Мы были созданы с участием иностранного инвестора...</p>
            <div class="eco-friendly">
                <img class="zoomIn wow" data-wow-delay=".8s" data-wow-duration=".5s" src="/img/about/image2.png" alt="">
                <p class="fadeInRight wow" data-wow-delay=".5s" data-wow-duration=".3s">Экологически чистая продукция для оптимального питания растений</p>
            </div>'
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
