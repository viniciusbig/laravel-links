<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Link;
use Auth;

class LinkController extends Controller
{

    public function index()
    {
        $links = Auth::user()->links()
            ->with('latestVisit')
            ->withCount('visits')
            ->get();

        return view('links.index', [
            'links' => $links
        ]);
    }

    public function create()
    {
        return view('links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'link' => 'required|url'
        ]);

        $link = Auth::user()->links()
            ->create($request->only(['name', 'link']));

        if ($link) {
            return redirect()->to('/dashboard/links');
        }

        return redirect()->back();
    }

    public function edit(Link $link)
    {
        if ($link->user_id !== Auth::id()) {
            return abort(404);
        }

        return view('links.edit', [
            'link' => $link
        ]);
    }

    public function update(Link $link, Request $request)
    {
        if ($link->user_id !== Auth::id()) {
            return abort(403);
        }

        $link->update($request->only(['name', 'link']));

        return redirect()->to('/dashboard/links');
    }

    public function destroy(Link $link, Request $request)
    {

    }

}
