<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    public function show($slug)
    {
        request()->attributes->set('tracked_link_slug', $slug);

        $user = User::where('links_slug', $slug)->firstOrFail();
        $links = Link::query()->where('active', true)->orderBy('position')->get();
        SiteVisit::create([
            'type' => 'links',
            'path' => request()->path(),
            'ip_hash' => hash('sha256', (string) request()->ip()),
            'user_agent' => substr((string) request()->userAgent(), 0, 500),
        ]);
        
        return view('site.links', [
            'user' => $user,
            'links' => $links,
        ]);
    }
}
