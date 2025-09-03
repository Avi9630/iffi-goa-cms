<?php

namespace App\Http\Controllers;

use App\Models\Ticker;
use Illuminate\Http\Request;

class TickerController extends Controller
{
    function index()
    {
        $tickers = Ticker::where('status', 1)->orderBy('sort_num', 'asc')->get();
        // $tickers = Ticker::orderBy('id','DESC')->get();
        return view('ticker.index', [
            'tickers' => $tickers,
        ]);
    }

    function toggleStatus($id)
    {
        $ticker = Ticker::findOrFail($id);
        $ticker->status = $ticker->status === 1 ? 0 : 1;
        $ticker->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function create()
    {
        return view('ticker.create');
    }

    function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'sort_num' => 'required|unique:tickers,sort_num',
        ]);
        Ticker::create([
            'content' => $request->content,
            'sort_num' => $request->sort_num,
        ]);
        return redirect()->route('ticker.index')->with('success', 'Ticker created successfully.');
    }

    function edit($id)
    {
        $ticker = Ticker::findOrFail($id);
        return view('ticker.edit', [
            'ticker' => $ticker,
        ]);
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'sort_num' => 'required|unique:tickers,sort_num',
        ]);
        $ticker = Ticker::findOrFail($id);
        $ticker->content = $request->content ?? $ticker->content;
        $ticker->sort_num = $request->sort_num ?? $ticker->sort_num;
        $ticker->save();
        return redirect()->route('ticker.index')->with('success', 'Ticker updated successfully.');
    }

    function destroy($id)
    {
        $ticker = Ticker::findOrFail($id);
        $ticker->delete();
        return redirect()->route('ticker.index')->with('success', 'Ticker deleted successfully.');
    }
}
