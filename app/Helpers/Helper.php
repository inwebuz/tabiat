<?php

namespace App\Helpers;

use App\Banner;
use App\BannerStats;
use App\Category;
use App\Page;
use App\Review;
use App\Setting;
use App\StaticText;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Parsedown;
use TCG\Voyager\Facades\Voyager;

class Helper
{

    public static function formatPrice($number)
    {
        return number_format($number, 0, '.', ' ') . ' ' . __('main.currency');
    }

    public static function formatDate(Carbon $date, $year = false)
    {
        $yearFormat = ($year) ? ', Y' : '';
        return __($date->format('F')) . ' ' . $date->format('d' . $yearFormat);
    }

    public static function formatDateSecond(Carbon $date)
    {
        return '<div>' . $date->format('d') . '</div><div>' . __($date->format('F')) . '</div>';
    }

    public static function formatViews($views = 0)
    {
        $text = $views . ' <span class="d-none d-lg-inline">';
        if (Str::endsWith($views, [11, 12, 13, 14])) {
            $text .= 'просмотров';
        } elseif (Str::endsWith($views, [2, 3, 4])) {
            $text .= 'просмотра';
        } elseif (Str::endsWith($views, 1)) {
            $text .= 'просмотр';
        } else {
            $text .= 'просмотров';
        }
        return $text . '</span>';
    }

    public static function formatOffers($offers = 0)
    {
        $text = $offers . ' ';
        if (Str::endsWith($offers, [11, 12, 13, 14])) {
            $text .= 'предложений';
        } elseif (Str::endsWith($offers, [2, 3, 4])) {
            $text .= 'предложения';
        } elseif (Str::endsWith($offers, 1)) {
            $text .= 'предложение';
        } else {
            $text .= 'предложений';
        }
        return $text;
    }

    public static function menuItems($type = '')
    {
        $menu = [];
        $query = Page::active();
        if ($type == 'header') {
            $query->inHeaderMenu();
        } elseif ($type == 'footer') {
            $query->inFooterMenu();
        }
        $pages = $query->whereNull('parent_id')->orderBy('order')->with(['pages' => function ($q) {
            $q->active()->with(['pages' => function ($q1) {
                $q1->active()->withTranslation(app()->getLocale());
            }])->withTranslation(app()->getLocale());
        }])->withTranslation(app()->getLocale())->get();
        if (!$pages->isEmpty()) {
            $pages = $pages->translate();
            foreach ($pages as $page) {
                $item = new MenuItem(new LinkItem($page->short_name_text, $page->url));
                $subPages = $page->pages;
                if (!$subPages->isEmpty()) {
                    $subPages = $subPages->translate();
                    foreach ($subPages as $subPage) {
                        $subPageItem = new MenuItem(new LinkItem($subPage->short_name_text, $subPage->url));
                        $subPagePages = $subPage->pages;
                        if (!$subPagePages->isEmpty()) {
                            $subPagePages = $subPagePages->translate();
                            foreach ($subPagePages as $subPagePage) {
                                $subPagePageItem = new MenuItem(new LinkItem($subPagePage->short_name_text, $subPagePage->url));
                                $subPageItem->addItem($subPagePageItem);
                            }
                        }
                        $item->addItem($subPageItem);
                    }
                }
                $menu[] = $item;
            }
        }
        return $menu;
    }

    public static function categories($type = 'menu')
    {
        $showIn = [Category::SHOW_IN_MENU, Category::SHOW_IN_EVERYWHERE];
        if ($type == 'home') {
            $showIn = [Category::SHOW_IN_HOME, Category::SHOW_IN_EVERYWHERE];
        }
        $locale = app()->getLocale();
        $query = Category::active()->withTranslation($locale)->whereIn('show_in', $showIn);
        if ($type == 'menu') {
            $query->whereNull('parent_id')->with(['children' => function ($query) use ($locale, $showIn) {
                $query->active()->withTranslation($locale)->whereIn('show_in', $showIn);
            }]);
        }
        $categories = $query->get();
        if (!$categories->isEmpty()) {
            $categories = $categories->translate();
        }
        return $categories;
    }

    public static function banner($type)
    {
        // $banner = Banner::where('type', $type)->active()->nowActive()->latest()->first();
        $banner = Banner::where('type', $type)->active()->nowActive()->withTranslation(app()->getLocale())->latest()->first();
        if (!$banner) {
            $banner = Banner::where([['type', $type], ['shop_id', null]])->withTranslation(app()->getLocale())->active()->latest()->first();
        }
        if (!$banner) {
            $banner = new Banner(['id' => 0, 'name' => '1', 'url' => '', 'image' => 'no-image.jpg']);
        }
        $banner = $banner->translate();
        return $banner;
    }

    public static function banners($type)
    {
        // $banners = Banner::where('type', $type)->active()->nowActive()->latest()->get();
        $banners = Banner::where('type', $type)->active()->withTranslation(app()->getLocale())->orderBy('order')->latest()->get();
        if (!$banners) {
            $banners = Banner::where([['type', $type], ['shop_id', null]])->active()->withTranslation(app()->getLocale())->latest()->get();
        }
        $banners = $banners->translate();
        return $banners;
    }

    public static function markdownToHtml($string)
    {
        $parsedown = new Parsedown();
        return $parsedown->text($string);
    }
    /**
     * Update currencies
     *
     * @return void
     */
    public static function updateCurrencies()
    {
        $currencies = Setting::whereIn('key', ['currency.usd', 'currency.eur'])->get();
        foreach ($currencies as $currency) {
            $currencyShortCode = mb_strtoupper(str_replace('currency.', '', $currency->key));
            try {
                $client = new Client([
                    'base_uri' => 'http://cbu.uz/uzc/arkhiv-kursov-valyut/',
                    'timeout' => 2.0,
                ]);
                $response = $client->get('xml/' . $currencyShortCode . '/');
                $xml = new \SimpleXMLElement($response->getBody()->getContents());
                $currency->value = $xml->CcyNtry->Rate;
                $currency->save();
            } catch (\Exception $e) {
            }
        }
    }

    public static function phone($phone)
    {
        $phone = preg_replace('#[^\d]#', '', $phone);
        if (Str::startsWith($phone, '998')) {
            $phone = '+' . $phone;
        }
        return $phone;
    }

    public static function parsePhones($phones)
    {
        $parsed = [];
        $phones = str_replace([';'], ',', $phones);
        $phones = explode(',', $phones);
        foreach ($phones as $phone) {
            $parsed[] = [
                'original' => $phone,
                'clean' => self::phone($phone),
            ];
        }
        return $parsed;
    }

    public static function reformatText($text)
    {
        return preg_replace(['#\*(.*?)\*#', '#\#(.*?)\##', '#\|\|#'], ['<strong>$1</strong>', '<span class="text-primary">$1</span>', '<br>'], $text);
    }

    public static function formatWorkDays($days)
    {
        $days = explode(',', preg_replace('#[^0-9,]#', '', $days));
        $days = array_map('intval', $days);
        $daysStatus = [];
        for ($i = 1; $i <= 7; $i++) {
            $daysStatus[$i] = in_array($i, $days) ? true : false;
        }
        return $daysStatus;
    }

    /**
     *
     * @return array
     */
    public static function languageSwitcher()
    {
        $route = Route::current();
        $routeName = Route::currentRouteName();
        $switcher = new LanguageSwitcher();
        $currentLocale = app()->getLocale();

        $foundModel = false;
        $hasSlugRoutes = ['page', 'category', 'publications.show', 'product'];
        foreach($hasSlugRoutes as $hasSlugRoute) {
            if ($routeName == $hasSlugRoute) {
                $routeParams = array_values($route->parameters);
                $model = array_shift($routeParams);
                $url = $model->url;

                foreach (config('laravellocalization.supportedLocales') as $key => $value) {
                    $model = $model->translate($key);
                    $value['url'] = $model->getModel()->getURL($key);
                    $linkItem = new LinkItem($value['native'], $value['url']);
                    $linkItem->key = $key;
                    if ($key == $currentLocale) {
                        $switcher->setActive($linkItem);
                    }
                    $switcher->addValue($linkItem);
                }
                $foundModel = true;
                break;
            }
        }

        if (!$foundModel) {
            $url = url()->current();
            foreach (config('laravellocalization.supportedLocales') as $key => $value) {
                $value['url'] = LaravelLocalization::localizeURL($url, $key);
                $linkItem = new LinkItem($value['native'], $value['url']);
                $linkItem->key = $key;
                if ($key == $currentLocale) {
                    // $linkItem->setActive();
                    $switcher->setActive($linkItem);
                }
                $switcher->addValue($linkItem);
            }
        }

        return $switcher;
    }

    public static function getActiveLanguageRegional()
    {
        $currentLocale = app()->getLocale();
        $locales = config('laravellocalization.supportedLocales');
        return $locales[$currentLocale]['regional'];
    }

    /**
     *
     * @return App\CustomSetting
     */
    public static function getSettings()
    {
        $settings = CustomSetting::findOrFail(1);
        $settings = self::translation($settings);
        return $settings;
    }

    public static function translation($model)
    {
        if (app()->getLocale() != config('voyager.multilingual.default')) {
            return $model->translate();
        }
        return $model;
    }

    /**
     * Send message via telegram bot to group
     */
    public static function toTelegram($text, $parse_mode = 'HTML', $chat_id = '')
    {
        $token = config('services.telegram.bot_token');

        if (!$chat_id) {
            $chat_id = config('services.telegram.chat_id');
        }

        $formData = [];
        $formData['chat_id'] = $chat_id;
        $formData['text'] = $text;
        if (in_array($parse_mode, ['HTML', 'Markdown'])) {
            $formData['parse_mode'] = $parse_mode;
        }

        try {
            $client = new Client([
                'base_uri' => 'https://api.telegram.org',
                'timeout' => 2.0,
            ]);

            $client->post('/bot' . $token . '/sendMessage', [
                'form_params' => $formData,
            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * Send message via telegram bot to group
     */
    public static function documentToTelegram($filePath, $fileName, $caption, $parse_mode = 'HTML', $chat_id = '')
    {
        $token = config('services.telegram.bot_token');

        if (!$chat_id) {
            $chat_id = config('services.telegram.chat_id');
        }
        if (!in_array($parse_mode, ['HTML', 'Markdown'])) {
            $parse_mode = 'HTML';
        }

        $multiparData = [
            [
                'name'     => 'chat_id',
                'contents' => $chat_id,
            ],
            [
                'name'     => 'caption',
                'contents' => $caption,
            ],
            [
                'name'     => 'document',
                'contents' => fopen($filePath, 'r'),
                'filename' => $fileName,
            ],
            [
                'name'     => 'parse_mode',
                'contents' => $parse_mode,
            ],
        ];

        try {
            $client = new Client([
                'base_uri' => 'https://api.telegram.org',
                'timeout'  => 2.0,
            ]);

            $result = $client->post('/bot' . $token . '/sendDocument', [
                'multipart' => $multiparData,
            ]);
            // Log::info($result);
        } catch (Exception $e) {
            Log::info(print_r($e, true));
        }
    }

    public static function storeFile($model, $field, $dir, $isImage = false)
    {
        if (request()->has($field)) {
            $url = request()->$field->store($dir . '/' . date('FY'), 'public');
            if (!$isImage) {
                $url = json_encode([
                    [
                        'download_link' => $url,
                        'original_name' => request()->$field->getClientOriginalName(),
                    ]
                ]);
            }
            $model->update([
                $field => $url,
            ]);
        }
        return $model;
    }

    public static function storeImage($model, $field, $dir, $thumbs = [])
    {
        $model = self::storeFile($model, $field, $dir, true);
        if ($thumbs && $model->$field) {
            $image = Image::make(storage_path('app/public/' . $model->$field));
            $image->backup();
            if ($image) {
                $ext = mb_strrchr($model->$field, '.');
                $pos = mb_strrpos($model->$field, '.');
                $fileName = mb_substr($model->$field, 0, $pos);
                foreach ($thumbs as $key => $value) {
                    $image->fit($value[0], $value[1])->save(storage_path('app/public/' . $fileName . '-' . $key . $ext));
                    $image->reset();
                }
            }
        }
        return $model;
    }

    public static function createModelImage($imagePath, $model, $dir, $thumbs = [])
    {
        $newImageDir = storage_path('app/public/') . $dir . '/' . date('FY') . '/';
        if (!is_dir($newImageDir)) {
            mkdir($newImageDir, 0755, true);
        }
        $fileBaseName = basename($imagePath);
        $newImagePath = $newImageDir . $fileBaseName;
        $result = File::move($imagePath, $newImagePath);
        if ($result) {
            $modelImage = $dir . '/' . date('FY') . '/' . $fileBaseName;
            $model->image = $modelImage;
            $model->save();
            if ($thumbs) {
                $image = Image::make($newImagePath);
                $image->backup();
                if ($image) {
                    $ext = mb_strrchr($fileBaseName, '.');
                    $pos = mb_strrpos($fileBaseName, '.');
                    $fileName = mb_substr($fileBaseName, 0, $pos);
                    foreach ($thumbs as $key => $value) {
                        $image->fit($value[0], $value[1])->save($newImageDir . $fileName . '-' . $key . $ext);
                        $image->reset();
                    }
                }
            }
        }
        return $model;
    }

    public static function createModelImages($imagePaths, $model, $dir, $thumbs = [])
    {
        $savedImages = [];
        foreach($imagePaths as $imagePath) {
            $newImageDir = storage_path('app/public/') . $dir . '/' . date('FY') . '/';
            if (!is_dir($newImageDir)) {
                mkdir($newImageDir, 0755, true);
            }
            $fileBaseName = basename($imagePath);
            $newImagePath = $newImageDir . $fileBaseName;
            $result = File::move($imagePath, $newImagePath);
            if ($result) {
                $savedImages[] = [
                    'newImageDir' => $newImageDir,
                    'fileBaseName' => $fileBaseName,
                    'newImagePath' => $newImagePath,
                    'modelImage' => $dir . '/' . date('FY') . '/' . $fileBaseName,
                ];
            }
        }

        if ($savedImages) {
            $modelImages = [];
            foreach($savedImages as $savedImage) {
                $modelImages[] = $savedImage['modelImage'];
                $newImageDir = $savedImage['newImageDir'];
                $fileBaseName = $savedImage['fileBaseName'];
                $newImagePath = $savedImage['newImagePath'];
                if ($thumbs) {
                    $image = Image::make($newImagePath);
                    $image->backup();
                    if ($image) {
                        $ext = mb_strrchr($fileBaseName, '.');
                        $pos = mb_strrpos($fileBaseName, '.');
                        $fileName = mb_substr($fileBaseName, 0, $pos);
                        foreach ($thumbs as $key => $value) {
                            $image->fit($value[0], $value[1])->save($newImageDir . $fileName . '-' . $key . $ext);
                            $image->reset();
                        }
                    }
                }
            }
            $model->images = json_encode($modelImages);
            $model->save();
        }
        return $model;
    }

    public static function createModelImageThumbs($imagePath, $model, $dir, $thumbs)
    {}

    public static function checkModelActive($model)
    {
        $className = get_class($model);
        if (!isset($model->status) || !defined("$className::STATUS_ACTIVE") || (int)$model->status !== $className::STATUS_ACTIVE) {
            abort(404);
        }
    }

    public static function staticText($key, $cacheTime = 21600)
    {
        $locale = app()->getLocale();
        $cacheKey = $locale . '-' . $key;
        return Cache::remember($cacheKey, $cacheTime, function () use ($key, $locale) {
            return StaticText::where('key', $key)->withTranslation($locale)->first();
        });
    }

    public static function seoTemplate($model, $name, $replacements = [])
    {
        $texts = [
            'seo_template_' . $name . '_seo_title',
            'seo_template_' . $name . '_meta_description',
            'seo_template_' . $name . '_meta_keywords',
            'seo_template_' . $name . '_description',
            'seo_template_' . $name . '_body',
        ];
        foreach ($texts as $text) {
            $currentProperty = str_replace('seo_template_' . $name . '_', '', $text);
            if (empty($model->$currentProperty)) {
                $template = self::staticText($text);
                if ($template) {
                    $model->$currentProperty = self::replaceTemplates($template->description, $replacements);
                }
            }
        }
        return $model;
    }

    public static function replaceTemplates($text, $replacements = [])
    {
        if (!$replacements) {
            return $text;
        }
        return str_replace(
            array_map(
                function ($value) {
                    return '{' . $value . '}';
                },
                array_keys($replacements)
            ),
            array_values($replacements),
            $text
        );
    }

    public static function addInitialReview($model)
    {
        $data = [
            'name' => 'Админ',
            'body' => 'Отлично!',
            'rating' => 5,
            'status' => Review::STATUS_ACTIVE,
        ];
        $model->reviews()->create($data);
    }

    public static function sendSMS($messageId, $phoneNumber, $message)
    {
        Log::info($message);
        return true;
        $phoneNumber = preg_replace('#[^\d]#', '', $phoneNumber);
        $data = [
            ['phone' => $phoneNumber, 'text' => $message],
            // Если сообщения приходят в неправильной кодировке, используйте iconv:
            //['phone' => 'NUMBER', 'text' => utf8_encode(iconv('windows-1251', 'utf-8', 'TEXT'))],
        ];

        if (config('app.env') == 'local') {
            Log::info(print_r($data, true));
            return true;
        }

        $client = new Client();
        $query = [
            "login" => "",
            "password" => "",
            "data" => json_encode($data)
        ];
        try {
            $client->request('POST', 'http://83.69.139.182:8083/', [
                'form_params' => $query
            ]);
        } catch (RequestException $e) {
            Log::info($e->getMessage());
        }

        return true;
    }

    public static function messagePrefix()
    {
        $name = str_replace(' ', '', Str::lower(config('app.name')));
        $prefix = config('app.env') == 'production' ? $name : 'test' . $name;
        return $prefix;
    }

    public static function getTree($collection, $parent = null, $level = 1)
    {
        $filtered = $collection->filter(function ($value) use ($parent) {
            return $value['parent_id'] == $parent;
        });
        $filtered->map(function ($item) use ($collection, $level) {
            $item['children'] = self::getTree($collection, $item->id, $level + 1);
        });
        return $filtered;
    }

    public static function activeCategories($category, $ids = [])
    {
        $ids[] = $category->id;
        if ($category->parent) {
            $ids = self::activeCategories($category->parent, $ids);
        }
        return $ids;
    }

    public static function siblingPages(Page $page)
    {
        if ($page->parent_id) {
            $siblingPages = Page::active()->where('parent_id', $page->parent_id)->with(['pages' => function ($q) {
                $q->active()->withTranslation(app()->getLocale());
            }])->get();
        } else {
            $siblingPages = Page::active()->whereNull('parent_id')->with(['pages' => function ($q) {
                $q->active()->withTranslation(app()->getLocale());
            }])->get();
        }
        if (!$siblingPages->isEmpty()) {
            $siblingPages = $siblingPages->translate();
        }
        return $siblingPages;
    }

    /**
     * Get file url
     */
    public static function getFileUrl($fileString)
    {
        $file = json_decode($fileString);
        return (!empty($file[0]->download_link)) ? Voyager::image($file[0]->download_link) : '';
        // return (!empty($file[0]->download_link)) ? Storage::disk(config('voyager.storage.disk'))->url($file[0]->download_link) : '';
    }

    /**
     * Get file original name
     */
    public static function getFileOriginalName($fileString)
    {
        $file = json_decode($fileString);
        return (!empty($file[0]->original_name)) ? $file[0]->original_name : '';
    }
}
