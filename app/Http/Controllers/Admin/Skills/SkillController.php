<?php

namespace App\Http\Controllers\Admin\Skills;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillRequest;
use App\Models\PortfolioSkill;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        $skills = PortfolioSkill::query()->orderBy('sort_order')->latest('id')->paginate(15);

        return view('admin.skills.index', compact('skills'));
    }

    public function create(): View
    {
        return view('admin.skills.form', [
            'skill' => new PortfolioSkill(),
            'mode' => 'create',
        ]);
    }

    public function store(StoreSkillRequest $request): RedirectResponse
    {
        PortfolioSkill::create($request->validated());

        return redirect()->route('admin.skills.index')->with('status', 'Skill tersimpan.');
    }

    public function edit(PortfolioSkill $skill): View
    {
        return view('admin.skills.form', [
            'skill' => $skill,
            'mode' => 'edit',
        ]);
    }

    public function update(StoreSkillRequest $request, PortfolioSkill $skill): RedirectResponse
    {
        $skill->update($request->validated());

        return redirect()->route('admin.skills.index')->with('status', 'Skill diperbarui.');
    }

    public function destroy(PortfolioSkill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('status', 'Skill dihapus.');
    }
}
