<?php

namespace App\Http\Controllers;

use App\Category;
use App\Page;
use App\Product;
use App\Publication;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function create()
    {
        $files = [];
        $oldFiles = ['sitemap.xml'];
        $oldFilesQty = 20;
        for ($i = 1; $i <= 20; $i++) {
            $oldFiles[] = 'sitemap' . $i . '.xml';
        }
        $all = [];
        $data = [
            'pages' => ['items' => Page::active()->withTranslations()->get(), 'priority' => 0.9, 'changeFrequency' => 'weekly', ],
            'categories' => ['items' => Category::active()->withTranslations()->get(), 'priority' => 0.8, 'changeFrequency' => 'weekly', ],
            'products' => ['items' => Product::active()->withTranslations()->get(), 'priority' => 0.7, 'changeFrequency' => 'weekly', ],
            'publications' => ['items' => Publication::active()->withTranslations()->get(), 'priority' => 0.7, 'changeFrequency' => 'weekly', ],
        ];
        foreach ($data as $type => $content) {
            if ($content['items']->isEmpty()) {
                continue;
            }
            foreach ($content['items'] as $item) {
                $locales = config('laravellocalization.supportedLocales');
                foreach($locales as $key => $locale) {
                    // $item = $item->translate($key);
                    $all[] = [
                        'url' => $item->getURL($key),
                        'priority' => $content['priority'],
                        'lastModificationDate' => $item->getModel()->updated_at->format(DateTime::ATOM) ?? date('Y-m-d'),
                        'changeFrequency' => $content['changeFrequency'],
                    ];
                }
            }
        }

        // echo "Links: " . count($all);

        $all = array_chunk($all, 9000);
        foreach ($all as $key => $urls) {
            $files[] = view('sitemap', compact('urls'))->render();
        }

        // get public folder path
        $publicDir = $this->getPublicDir();

        // delete old files
        foreach ($oldFiles as $oldFile) {
            $file = public_path($oldFile);
            if(file_exists($file)) {
                unlink($file);
            }
        }

        // write new sitemap files
        foreach ($files as $key => $value) {
            $fileName = $publicDir . '/' . 'sitemap' . ($key + 1) . '.xml';
            file_put_contents($fileName, $value);
        }

        // rewrite sitemap index file
        $filesQuantity = count($files);
        $sitemapLastmod = (Carbon::now())->format(DateTime::ATOM);
        $sitemapIndexContent = view('sitemapindex', compact('filesQuantity', 'sitemapLastmod'))->render();
        file_put_contents($publicDir . '/sitemapindex.xml', $sitemapIndexContent);
    }

    public function index()
    {
        $this->create();
        $publicDir = $this->getPublicDir();

        return response(file_get_contents($publicDir . '/sitemapindex.xml'))
            ->withHeaders([
                'Content-Type' => 'text/xml'
            ]);
    }

    private function getPublicDir()
    {
        $publicDir = base_path('../public_html');
        if (!is_dir($publicDir)) {
            $publicDir = public_path();
        }
        return $publicDir;
    }
}
