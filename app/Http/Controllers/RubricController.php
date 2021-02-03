<?php

namespace App\Http\Controllers;

use App\Company;
use App\Helpers\Breadcrumbs;
use App\Helpers\Helper;
use App\Helpers\LinkItem;
use App\Page;
use App\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\SchemaOrg\AggregateRating;
use Spatie\SchemaOrg\Schema;

class RubricController extends Controller
{
    public function index()
    {
        $page = Page::findOrFail(6);
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url, LinkItem::STATUS_INACTIVE));
        $rubrics = Rubric::active()->whereNull('parent_id')->get();
        return view('rubrics', compact('breadcrumbs', 'page', 'rubrics'));
    }

    public function view(Request $request, Rubric $rubric)
    {
        Helper::checkModelActive($rubric);
        $page = Page::findOrFail(6);
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->addItem(new LinkItem($page->name, $page->url));
        if ($rubric->parent) {
            $breadcrumbs->addItem(new LinkItem($rubric->parent->name, $rubric->parent->url));
        }
        $breadcrumbs->addItem(new LinkItem($rubric->name, $rubric->url, LinkItem::STATUS_INACTIVE));
        $subRubrics = Rubric::active()->where('parent_id', $rubric->id)->get();

        // fixed companies
        $fixedCompanies = $rubric->fixedCompanies()->with('company')->get();
        $fixedCompaniesIds = $fixedCompanies->pluck('company_id');

        // companies query
        $query = $rubric->companies()->active();

        // exclude fixed companies
        $query->whereNotIn('companies.id', $fixedCompaniesIds);

        // by default VIP companies first
        $query->orderBy('type', 'desc');

        // sort
        $sorts = $this->sorts();
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        foreach ($sorts as $key => $value) {
            if ($value['sort'] == $sort && $value['order'] == $order) {
                $sorts[$key]['active'] = true;
                break;
            }
        }
        $query->orderBy($sort, $order);

        // filter
        $filters = $this->filters();
        $filter = $request->input('filter', '');
        foreach ($filters as $key => $value) {
            if ($value['value'] == $filter) {
                $filters[$key]['active'] = true;
                break;
            }
        }
        if ($filter) {
            $query->where('name', 'like', $filter . '%');
        }
        // quantities
        $quantities = $this->quantities();
        $quantity = $request->input('quantity', 30);
        foreach ($quantities as $key => $value) {
            if ($value == $quantity) {
                $quantities[$key]['active'] = true;
                break;
            }
        }

        // microdata
        $itemIds = $query->get()->pluck('id');
        if ($itemIds) {
            $reviewsInfo = DB::table('reviews')->selectRaw('count(*) count, avg(rating) avg, sum(rating) sum')->where('status', 1)->where('reviewable_type', Company::class)->whereIn('reviewable_id', $itemIds)->first();
            $reviewsCount = $reviewsInfo->count;
            $reviewsAvg = round($reviewsInfo->avg, 1);

            $microdata = Schema::product();
            $microdata->name($page->name);
            $aggregateRating = new AggregateRating();
            $aggregateRating->worstRating(1)->bestRating(5)->ratingCount($reviewsCount)->ratingValue($reviewsAvg);
            $microdata->aggregateRating($aggregateRating);
            $microdata = $microdata->toScript();
        } else {
            $microdata = '';
        }

        // get companies
        $companies = $query->paginate($quantity);

        // SEO templates
        $rubric = Helper::seoTemplate($rubric, 'rubric', ['name' => $rubric->name]);

        // add item text
        $addItemText = Helper::replaceTemplates(Helper::staticText('add_company_text')->description, ['rubric_name' => $rubric->name]);

        return view('rubric', compact('rubric', 'breadcrumbs', 'subRubrics', 'fixedCompanies', 'companies', 'sorts', 'sort', 'order', 'quantities', 'quantity', 'filters', 'filter', 'addItemText', 'microdata'));
    }

    private function sorts()
    {
        return [
            [
                'text' => 'По имени',
                'sort' => 'name',
                'order' => 'asc',
                'active' => false,
            ],
            [
                'text' => 'По имени',
                'sort' => 'name',
                'order' => 'desc',
                'active' => false,
            ],
            [
                'text' => 'По дате',
                'sort' => 'created_at',
                'order' => 'asc',
                'active' => false,
            ],
            [
                'text' => 'По дате',
                'sort' => 'created_at',
                'order' => 'desc',
                'active' => false,
            ],
        ];
    }

    private function filters()
    {
        $filters = [];

        $alphabet1 = range('A', 'Z');
        $alphabet2 = range(chr(192), chr(223));
        foreach ($alphabet2 as $key => $value) {
            $alphabet2[$key] = iconv('CP1251', 'UTF-8', $value);
        }

        $alphabet = array_merge($alphabet1, $alphabet2);
        foreach($alphabet as $value) {
            $filters[] = [
                'text' => $value,
                'value' => $value,
                'active' => false,
            ];
        }
        return $filters;
    }

    private function quantities()
    {
        return [
            [
                'value' => 30,
                'active' => false,
            ],
            [
                'value' => 60,
                'active' => false,
            ],
        ];
    }
}
