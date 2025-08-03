<?php

namespace App\Http\Controllers;

use App\Models\LatestUpdate;
use Illuminate\Http\Request;

class LatestUpdateController extends Controller
{
    function index()
    {
        $latestUpdates = LatestUpdate::paginate(10);
        return view('latest-update.index', compact('latestUpdates'));
    }

    function create()
    {
        return view('latest-update.create');
    }

    function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'link' => 'required|url',
        ]);

        LatestUpdate::create([
            'content' => $request->content,
            'title' => $request->title,
            'link' => $request->link,
            'status' => $request->status?? 1, // Default to active if not provided
        ]);

        return redirect()->route('latest-update.index')->with('success', 'Latest Update created successfully.');
    }

    function edit($id)
    {
        $latestUpdate = LatestUpdate::findOrFail($id);
        return view('latest-update.edit', compact('latestUpdate'));
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'link' => 'required|url',
        ]);

        $latestUpdate = LatestUpdate::findOrFail($id);
        $latestUpdate->update([
            'content' => $request->content,
            'title' => $request->title,
            'link' => $request->link,
        ]);

        return redirect()->route('latest-update.index')->with('success', 'Latest Update updated successfully.');
    }

    function destroy($id)
    {
        $latestUpdate = LatestUpdate::findOrFail($id);
        $latestUpdate->delete();
        return redirect()->route('latest-update.index')->with('success', 'Latest Update deleted successfully.');
    }

    function toggleStatus($id)
    {
        $latestUpdate = LatestUpdate::findOrFail($id);
        $latestUpdate->status = $latestUpdate->status === 1 ? 0 : 1;
        $latestUpdate->save();
        return redirect()->back()->with('success', 'Status updated successfully.'); 
    }
}
