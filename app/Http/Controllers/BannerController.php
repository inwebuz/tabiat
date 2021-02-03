<?php

namespace App\Http\Controllers;

use App\Banner;
use App\BannerStats;
use App\Publication;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function incrementClicks(Banner $banner)
    {
        $bannerStats = BannerStats::firstOrCreate(['date' => date('Y-m-d'), 'banner_id' => $banner->id]);
        $bannerStats->increment('clicks');
        return '';
    }

    public function incrementViews(Banner $banner)
    {
        $bannerStats = BannerStats::firstOrCreate(['date' => date('Y-m-d'), 'banner_id' => $banner->id]);
        $bannerStats->increment('views');
        return '';
    }
}
