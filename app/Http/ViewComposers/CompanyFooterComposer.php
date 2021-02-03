<?php

namespace App\Http\ViewComposers;

use App\Company;
use App\Rubric;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class CompanyFooterComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $company = Route::input('company');
        $rubrics = $company->rubrics->pluck('id');
        $companies = Company::active()->latest()->whereHas('rubrics', function($query) use ($rubrics) {
            $query->whereIn('rubric_id', $rubrics);
        })->take(10)->get();
        $view->with(compact('rubric', 'companies'));
    }
}
