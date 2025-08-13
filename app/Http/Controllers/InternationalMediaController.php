<?php

namespace App\Http\Controllers;

use App\Models\InternationalMedia;
use Illuminate\Http\Request;

class InternationalMediaController extends Controller
{
    function index()
    {
        $internationalMedias = InternationalMedia::orderBy('id', 'DESC')->paginate();
        return view('international_media.index', compact('internationalMedias'));
    }

    function toggleStatus($id)
    {
        $ticker = InternationalMedia::findOrFail($id);
        $ticker->status = $ticker->status === 1 ? 0 : 1;
        $ticker->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function create()
    {
        return view('international_media.create');
    }

    function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required',
        ]);

        $internationalMedia = new InternationalMedia();
        $internationalMedia->title = $request->title ?? null;
        $internationalMedia->urls = $request->url ?? null;
        $internationalMedia->save();
        return redirect()->route('international-media.index')->with('success', 'Press Release created successfully.');
    }

    function edit($id)
    {
        $internationalMedia = InternationalMedia::findOrFail($id);
        return view('international_media.edit', compact('internationalMedia'));
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|required_without:image',
        ]);
        $internationalMedia = InternationalMedia::findOrFail($id);
        $internationalMedia->title = $request->title ?? null;
        $internationalMedia->urls = $request->url ?? null;
        $internationalMedia->save();
        return redirect()->route('international-media.index')->with('success', 'Updated successfully.!!');
    }

    function destroy($id)
    {
        $pressRelease = InternationalMedia::findOrFail($id);
        $pressRelease->delete();
        return redirect()->route('press-release.index')->with('danger', 'Press Release deleted successfully.!!');
    }
}
