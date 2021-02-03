<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SynchroPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchro:photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize torgsoft photos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Synchronization start');

        $storage = realpath(storage_path('app/torgsoft-images/')) . DIRECTORY_SEPARATOR;
        $lastProductID = Cache::get('last-synchro-product-id', 0);
        $this->info($lastProductID);
        $products = Product::where('status', Product::STATUS_PENDING)->orderBy('id')->where('id', '>', $lastProductID)->with('productVariants')->take(500)->get();
        if (!$products->isEmpty()) {
            foreach ($products as $product) {
                $variants = $product->productVariants;
                foreach ($variants as $variant) {
                    // get images
                    $getImage = glob($storage . $variant->external_id . '.*');
                    $getImages = glob($storage . $variant->external_id . '_*.*');

                    // add main image
                    if (!$product->image && count($getImage)) {
                        $imagePath = $getImage[0];
                        $product = Helper::createModelImage($imagePath, $product, 'products', Product::$imgSizes);
                    } elseif (count($getImage)) {
                        // delete extra pictures
                        unlink($getImage[0]);
                    }

                    // add additional images
                    if (!$product->images && count($getImages)) {
                        $product = Helper::createModelImages($getImages, $product, 'products', Product::$imgSizes);
                    } elseif(count($getImages)) {
                        // delete extra pictures
                        foreach ($getImages as $image) {
                            unlink($image);
                        }
                    }
                }
                if ($product->image) {
                    $product->status = Product::STATUS_ACTIVE;
                    $product->save();
                }

                $lastProductID = $product->id;
            }
            Cache::put('last-synchro-product-id', $lastProductID);
        } else {
            Cache::put('last-synchro-product-id', 0);
        }
        $this->info('Synchronization end');
        return 0;
    }
}
