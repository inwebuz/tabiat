<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\LinkItem;
use Illuminate\Http\Request;
use App\Article;
use App\Page;
use App\Helpers\Helper;

class ArticleController extends Controller
{
    /**
     * show all articles
     */
    public function index()
    {
        $breadcrumbs = new Breadcrumbs();

        $page = Page::find(4); // articles page
        if(!$page) {
            abort(404);
        }
        $page = Helper::translation($page);

        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $articles = Article::active()->latest()->paginate(10);

        return view('articles', compact('breadcrumbs', 'page', 'articles'));
    }

    /**
     * show single article
     */
    public function view(Article $article)
    {
        $breadcrumbs = new Breadcrumbs();

        $page = Helper::translation(Page::find(4)); // articles page
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url));

        $article = Helper::translation($article);
        $breadcrumbs->addItem(new LinkItem($article->name, $article->url, LinkItem::STATUS_INACTIVE));

        return view('article', compact('breadcrumbs', 'page', 'article'));
    }
}
