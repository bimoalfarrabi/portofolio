<?php

namespace App\Http\Controllers;

use App\Models\PortfolioCollab;
use App\Models\PortfolioLog;
use App\Models\PortfolioProject;
use App\Models\PortfolioSkill;
use App\Models\PortfolioStat;

class PortfolioController extends Controller
{
    public function __invoke()
    {
        return view('welcome', [
            'locale'        => app()->getLocale(),
            'portfolioData' => [
                'projects' => PortfolioProject::query()
                    ->where('is_published', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->map(fn ($p) => array_merge($p->toArray(), [
                        'name'        => $p->trans('title'),
                        'description' => $p->trans('description'),
                        'approach'    => $p->trans('approach'),
                        'outcome'     => $p->trans('outcome'),
                    ])),

                'skills' => PortfolioSkill::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(),

                'logs' => PortfolioLog::query()
                    ->where('is_published', true)
                    ->orderBy('sort_order')
                    ->get(),

                'stats' => PortfolioStat::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(),

                'collab' => (function () {
                    $c = PortfolioCollab::current();
                    return array_merge($c->toArray(), [
                        'available_label' => $c->trans('available_label'),
                        'busy_label'      => $c->trans('busy_label'),
                        'location'        => $c->trans('location'),
                        'time_zone_label' => $c->trans('time_zone_label'),
                        'response_time'   => $c->trans('response_time'),
                    ]);
                })(),
            ],
        ]);
    }
}
