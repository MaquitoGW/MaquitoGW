<?php

namespace App\Http\Controllers\admin;

use App\Models\Link;
use App\Models\User;
use App\Services\AssetStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends AdminController
{
    public function index()
    {
        $user = User::query()->orderBy('id')->first() ?? Auth::user();
        $links = Link::query()->orderBy('position')->get();
        $slug = $user?->links_slug ?: 'links';

        return view('admin.links', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'links' => $links,
            'user' => $user,
            'publicLinkUrl' => rtrim(config('app.url'), '/') . '/' . $slug,
        ]);
    }

    public function create()
    {
        return view('admin.linksCreate', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['position'] = (int) Link::query()->max('position') + 1;

        Link::create($validated);

        return redirect()->route('links')->with('success', 'Link adicionado com sucesso!');
    }

    public function edit(Link $link)
    {
        return view('admin.linksEdit', [
            'customization' => fn($config, $else = null) => $this->search($config, $else),
            'link' => $link,
        ]);
    }

    public function update(Request $request, Link $link)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $link->update($validated);

        return redirect()->route('links')->with('success', 'Link atualizado com sucesso!');
    }

    public function destroy(Link $link)
    {
        $link->delete();

        return redirect()->route('links')->with('success', 'Link removido com sucesso!');
    }

    public function toggleActive(Link $link)
    {
        $link->update(['active' => !$link->active]);

        return redirect()->route('links')->with('success', 'Link atualizado com sucesso!');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->validate([
            'order' => 'required|array',
        ])['order'];

        foreach ($order as $position => $linkId) {
            Link::where('id', $linkId)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }

    public function updateSlug(Request $request)
    {
        $user = User::query()->orderBy('id')->firstOrFail();

        $validated = $request->validate([
            'links_slug' => 'required|string|max:50|regex:/^[a-zA-Z0-9_-]+$/|unique:users,links_slug,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('links')->with('success', 'URL da página de links atualizada com sucesso!');
    }

    public function updateProfile(Request $request)
    {
        $user = User::query()->orderBy('id')->firstOrFail();
        $storage = app(AssetStorageService::class);

        $validated = $request->validate([
            'links_display_name' => 'nullable|string|max:255',
            'links_bio' => 'nullable|string|max:500',
            'links_avatar' => 'nullable|image|max:2048',
            'links_banner' => 'nullable|image|max:4096',
        ]);

        foreach (['links_avatar', 'links_banner'] as $field) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                if (!empty($user->{$field})) {
                    $storage->delete($user->{$field});
                }

                $image = $request->file($field);
                $imageName = md5($field . $image->getClientOriginalName() . now()) . '.' . $image->extension();
                $validated[$field] = $storage->putUploadedFile($image, 'links', $imageName);
            } else {
                unset($validated[$field]);
            }
        }

        $user->update($validated);

        return redirect()->route('links')->with('success', 'Perfil da página de links atualizado com sucesso!');
    }

}
