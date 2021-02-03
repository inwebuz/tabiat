<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Publication;
use Illuminate\Support\Carbon;
use Exception;
use IntlDateFormatter;
use Spatie\SchemaOrg\AggregateRating;
use Spatie\SchemaOrg\Schema;

class PublicationController extends Controller
{
    public function index()
    {
        //
    }

    public function news()
    {
        $page = Page::where('slug', 'news')->active()->firstOrFail();

        $paginationPage = request('page', 1);
        if (!is_int($paginationPage)) {
            $paginationPage = 1;
        }

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->news()->latest()->withTranslation(app()->getLocale());

        $today = Carbon::now();
        $end = request('end', $today->format('d.m.Y'));
        $start = request('start', $today->subMonths(3)->format('d.m.Y'));
        try {
            $periodStart = Carbon::createFromFormat('d.m.Y', $start);
            $periodEnd = Carbon::createFromFormat('d.m.Y', $end);
            if($periodStart > $periodEnd) {
                $tempPeriod = $periodStart;
                $periodStart = $periodEnd;
                $periodEnd = $tempPeriod;
            }
            $query->where('created_at', '>=', $periodStart->startOfDay()->format('Y-m-d H:i:s'));
            $query->where('created_at', '<=', $periodEnd->endOfDay()->format('Y-m-d H:i:s'));
        } catch (Exception $e) {
            abort(404);
        }

        $publications = $query->paginate(12);

        $links = $publications->appends(['start' => Helper::formatDate($periodStart), 'end' => Helper::formatDate($periodEnd)])->links();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.news', compact('breadcrumbs', 'page', 'paginationPage', 'publications', 'links', 'siblingPages', 'periodEnd', 'periodStart'));
    }

    public function events()
    {
        $page = Page::where('slug', 'events')->active()->firstOrFail();

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->events()->latest()->withTranslation(app()->getLocale());

        $publications = $query->paginate(12);

        $links = $publications->links();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.publications', compact('breadcrumbs', 'page', 'publications', 'links', 'siblingPages'));
    }

    public function competitions()
    {
        $page = Page::where('slug', 'competitions')->active()->firstOrFail();

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->competitions()->latest()->withTranslation(app()->getLocale());

        $publications = $query->paginate(12);

        $links = $publications->links();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.publications', compact('breadcrumbs', 'page', 'publications', 'links', 'siblingPages'));
    }

    public function projects()
    {
        $page = Page::where('slug', 'projects')->active()->firstOrFail();

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->projects()->latest()->withTranslation(app()->getLocale());

        $publications = $query->paginate(12);

        $links = $publications->links();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.publications', compact('breadcrumbs', 'page', 'publications', 'links', 'siblingPages'));
    }

    public function ads()
    {
        $page = Page::where('slug', 'ads')->active()->firstOrFail();

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->ads()->latest()->withTranslation(app()->getLocale());

        $publications = $query->paginate(12);

        $links = $publications->links();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.publications', compact('breadcrumbs', 'page', 'publications', 'links', 'siblingPages'));
    }

    public function massMedia()
    {
        $page = Page::where('slug', 'mass-media')->active()->firstOrFail();

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->massMedia()->latest()->withTranslation(app()->getLocale());

        $publications = $query->paginate(12);

        $links = $publications->links();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.publications', compact('breadcrumbs', 'page', 'publications', 'links', 'siblingPages'));
    }

    public function usefulLInks()
    {
        $page = Page::where('slug', 'useful-links')->active()->firstOrFail();

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->usefulLInks()->latest()->withTranslation(app()->getLocale());

        $publications = $query->paginate(12);

        $links = $publications->links();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.publications', compact('breadcrumbs', 'page', 'publications', 'links', 'siblingPages'));
    }

    public function faq()
    {
        $page = Page::where('slug', 'faq')->active()->firstOrFail();

        $breadcrumbs = new Breadcrumbs();

        if ($page->parent_id) {
            $parentPage = $page->parent;
            $parentPage = Helper::translation($parentPage);
            $breadcrumbs->addItem(new LinkItem($parentPage->name, $parentPage->url));
        }

        $siblingPages = Helper::siblingPages($page);

        $page = Helper::translation($page);
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));

        $query = Publication::active()->faq()->latest()->withTranslation(app()->getLocale());

        $publications = $query->get();
        if ($publications) {
            $publications = $publications->translate();
        }

        return view('publications.faq', compact('breadcrumbs', 'page', 'publications', 'siblingPages'));
    }

    public function show(Publication $publication)
    {
        Helper::checkModelActive($publication);

        $breadcrumbs = new Breadcrumbs();

        $page = $publication->typePage();
        if ($page) {
            $page = Helper::translation($page);
            $breadcrumbs->addItem(new LinkItem($page->name, $page->url));
        }

        $publication->increment('views');

        /* $reviewQuery = $publication->reviews()->active();
        $reviews = $reviewQuery->latest()->take(20)->get();
        $reviewsCount = $reviewQuery->count();
        $reviewsAvg = round($reviewQuery->avg('rating'), 1);

        // SEO templates
        $publication = Helper::seoTemplate($publication, 'publications', ['name' => $publication->name]);

        $microdata = Schema::product();
        $microdata->name($publication->name);
        $aggregateRating = new AggregateRating();
        $aggregateRating->worstRating(1)->bestRating(5)->ratingCount($reviewsCount)->ratingValue($reviewsAvg);
        $microdata->aggregateRating($aggregateRating);
        $microdata = $microdata->toScript(); */

        $publication = Helper::translation($publication);
        $breadcrumbs->addItem(new LinkItem($publication->name, $publication->url, LinkItem::STATUS_INACTIVE));

        // return view('publications.show', compact('breadcrumbs', 'publication', 'page', 'reviews', 'microdata'));
        return view('publications.show', compact('breadcrumbs', 'publication', 'page'));
    }

    public function print(Publication $publication)
    {
        $publication = Helper::translation($publication);
        return view('publications.print', compact('publication'));
    }

    public function incrementViews(Publication $publication)
    {
        $publication->increment('views');
        return '';
    }
}
