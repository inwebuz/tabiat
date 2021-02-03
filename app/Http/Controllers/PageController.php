<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\LinkItem;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Page;

class PageController extends Controller
{
    /**
     * show single page
     */
    public function index(Page $page)
    {
        Helper::checkModelActive($page);
        $breadcrumbs = new Breadcrumbs();

        $page->increment('views');

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);

        if (!$page->parent_id) {
            $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));
        }

        return view('page.index', compact('breadcrumbs', 'page', 'siblingPages'));
    }

    public function print(Page $page)
    {
        $page = Helper::translation($page);
        return view('page.print', compact('page'));
    }
}
