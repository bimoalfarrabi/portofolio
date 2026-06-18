<?php

namespace App\Http\Controllers\Admin\Stats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStatRequest;
use App\Models\PortfolioStat;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StatController extends Controller
{
    public function index(): View
    {
        $stats = PortfolioStat::query()->orderBy('sort_order')->latest('id')->paginate(15);

        return view('admin.stats.index', compact('stats'));
    }

    public function create(): View
    {
        return view('admin.stats.form', [
            'stat' => new PortfolioStat(),
            'mode' => 'create',
        ]);
    }

    public function store(StoreStatRequest $request): RedirectResponse
    {
        PortfolioStat::create($request->validated());

        return redirect()->route('admin.stats.index')->with('status', 'Stat tersimpan.');
    }

    public function edit(PortfolioStat $stat): View
    {
        return view('admin.stats.form', [
            'stat' => $stat,
            'mode' => 'edit',
        ]);
    }

    public function update(StoreStatRequest $request, PortfolioStat $stat): RedirectResponse
    {
        $stat->update($request->validated());

        return redirect()->route('admin.stats.index')->with('status', 'Stat diperbarui.');
    }

    public function destroy(PortfolioStat $stat): RedirectResponse
    {
        $stat->delete();

        return redirect()->route('admin.stats.index')->with('status', 'Stat dihapus.');
    }
}
