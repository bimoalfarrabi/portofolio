<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLogRequest;
use App\Models\PortfolioLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(): View
    {
        $logs = PortfolioLog::query()->orderBy('sort_order')->latest('id')->paginate(15);

        return view('admin.logs.index', compact('logs'));
    }

    public function create(): View
    {
        return view('admin.logs.form', [
            'log' => new PortfolioLog(),
            'mode' => 'create',
        ]);
    }

    public function store(StoreLogRequest $request): RedirectResponse
    {
        PortfolioLog::create($request->validated());

        return redirect()->route('admin.logs.index')->with('status', 'Log tersimpan.');
    }

    public function edit(PortfolioLog $log): View
    {
        return view('admin.logs.form', [
            'log' => $log,
            'mode' => 'edit',
        ]);
    }

    public function update(StoreLogRequest $request, PortfolioLog $log): RedirectResponse
    {
        $log->update($request->validated());

        return redirect()->route('admin.logs.index')->with('status', 'Log diperbarui.');
    }

    public function destroy(PortfolioLog $log): RedirectResponse
    {
        $log->delete();

        return redirect()->route('admin.logs.index')->with('status', 'Log dihapus.');
    }
}
