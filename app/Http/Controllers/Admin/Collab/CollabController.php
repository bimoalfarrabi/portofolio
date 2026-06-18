<?php

namespace App\Http\Controllers\Admin\Collab;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCollabRequest;
use App\Models\PortfolioCollab;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CollabController extends Controller
{
    public function edit(): View
    {
        return view('admin.collab.form', [
            'collab' => PortfolioCollab::current(),
        ]);
    }

    public function update(UpdateCollabRequest $request): RedirectResponse
    {
        PortfolioCollab::current()->update($request->validated());

        return redirect()->route('admin.collab.edit')->with('status', 'Collab diperbarui.');
    }
}
