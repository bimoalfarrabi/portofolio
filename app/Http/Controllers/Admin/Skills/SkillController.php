<?php

namespace App\Http\Controllers\Admin\Skills;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillRequest;
use App\Models\PortfolioSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        $skills = PortfolioSkill::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy(fn ($s) => filled($s->category) ? $s->category : 'Lainnya');

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
        $data = $request->validated();
        // Auto-assign sort_order: append after last skill in same category
        $data['sort_order'] = PortfolioSkill::query()->max('sort_order') + 1;

        PortfolioSkill::create($data);

        return redirect()->route('admin.skills.index')->with('status', 'Skill tersimpan.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'exists:portfolio_skills,id'],
        ]);

        foreach ($validated['order'] as $position => $id) {
            PortfolioSkill::where('id', $id)->update(['sort_order' => $position + 1]);
        }

        return response()->json(['ok' => true]);
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
