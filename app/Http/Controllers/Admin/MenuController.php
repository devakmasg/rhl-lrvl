<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * The site menus — the header bar (with its dropdowns) and the footer's
 * Explore column.
 *
 * Labels and ordering are editable; a link's destination is chosen from the
 * pages that actually exist, so a menu item can never point at a URL this site
 * does not serve.
 */
class MenuController extends Controller
{
    public function index(): View
    {
        return view('admin.menus.index', [
            'menus' => Menu::with(['links.children'])->orderBy('id')->get(),
            'targets' => $this->availableTargets(),
        ]);
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:120'],
            'links' => ['array'],
            'links.*.id' => ['nullable', 'integer'],
            'links.*.label' => ['nullable', 'string', 'max:120'],
            'links.*.target' => ['nullable', 'string', 'max:255'],
            'links.*.parent_id' => ['nullable', 'integer'],
            'links.*.is_active' => ['nullable', 'boolean'],
        ]);

        $menu->update(['heading' => $this->clean($data['heading'] ?? null)]);

        $this->syncLinks($menu, $data['links'] ?? []);

        return redirect()
            ->route('admin.menus.index', ['#menu-'.$menu->id])
            ->with('status', 'Saved “'.$menu->label.'”.');
    }

    /**
     * Rewrite the menu's links from the submitted rows.
     *
     * Rows are matched by id so existing links keep theirs — that matters
     * because a child row names its parent by id. A row the admin blanked is
     * deleted, and deleting a parent takes its children with it (the foreign
     * key cascades), which is what the editor sees on screen.
     */
    private function syncLinks(Menu $menu, array $rows): void
    {
        $keptIds = [];
        $order = 0;

        foreach ($rows as $row) {
            $label = trim((string) ($row['label'] ?? ''));
            $target = trim((string) ($row['target'] ?? ''));

            if ($label === '' || $target === '') {
                continue;
            }

            $attributes = [
                'menu_id' => $menu->id,
                'parent_id' => $row['parent_id'] ?? null,
                'label' => $label,
                'target' => $target,
                'sort_order' => ++$order,
                'is_active' => (bool) ($row['is_active'] ?? false),
            ];

            $existing = ! empty($row['id'])
                ? MenuLink::where('menu_id', $menu->id)->find($row['id'])
                : null;

            if ($existing) {
                $existing->update($attributes);
                $keptIds[] = $existing->id;
            } else {
                $keptIds[] = MenuLink::create($attributes)->id;
            }
        }

        MenuLink::where('menu_id', $menu->id)->whereNotIn('id', $keptIds ?: [0])->delete();
    }

    /**
     * Route names an admin may link to: the public GET pages, minus the ones
     * that need a model bound into the URL (a project, a news article).
     *
     * @return array<string, string>
     */
    private function availableTargets(): array
    {
        $targets = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            if (! $name || ! in_array('GET', $route->methods(), true)) {
                continue;
            }

            if (Str::startsWith($name, 'admin.') || str_contains($route->uri(), '{')) {
                continue;
            }

            $targets[$name] = Str::headline(Str::replace(['.', '-'], ' ', $name));
        }

        ksort($targets);

        return $targets;
    }

    private function clean(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
