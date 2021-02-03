<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Product;
use App\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * show all articles
     */
    public function index(Request $request)
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem(__('main.nav.search'), route('search'), LinkItem::STATUS_INACTIVE));

        $q = $request->input('q', '');

        $searches = collect([]);

        if ($q && Str::length($q) >= 3) {

            $query = Search::where('body', 'like', '%' . $q . '%')
                ->with(['searchable' => function($q1) {
                    $q1->withTranslation(app()->getLocale());
                }]);

            // only products
            $query->where('searchable_type', Product::class);

            // get searches
            $searches = $query->paginate(12);

            if ($searches->isEmpty()) {
                $qArray = explode(' ', $q);
                if (count($qArray) > 0) {
                    $query = Search::where(function ($query) use ($qArray) {
                        foreach ($qArray as $qWord) {
                            if (mb_strlen($qWord) > 2) {
                                $query->orWhere('body', 'like', '%' . $qWord . '%');
                            }
                        }
                    })
                        ->with(['searchable' => function($q1) {
                            $q1->withTranslation(app()->getLocale());
                        }]);

                    // only products
                    $query->where('searchable_type', Product::class);

                    // get searches
                    $searches = $query->paginate(12);
                }
            }
        }

        $links = (!$searches->isEmpty() && $searches->hasMorePages()) ? $searches->appends(['q' => $q])->links() : '';

        // if (!$searches->isEmpty()) {
        //     dd($searches);
        //     $searches = $searches->translate();
        // }

        return view('search', compact('breadcrumbs', 'searches', 'links', 'q'));
    }
}
