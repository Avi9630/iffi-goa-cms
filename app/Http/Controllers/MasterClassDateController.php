<?php

namespace App\Http\Controllers;

use App\Models\MasterClassDate;
use Illuminate\Http\Request;

class MasterClassDateController extends Controller
{
    function index()
    {
        $masterDates = MasterClassDate::all();
        return view('master_date.index', compact('masterDates'));
    }

    function create()
    {
        return view('master_date.create');
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'year' => 'required',
        ]);
        $exists = MasterClassDate::where('date', $payload['date'])->where('year', $payload['year'])->exists();
        if ($exists) {
            return redirect()->back()->with('warning', 'Date already available.!!');
        }
        $create = MasterClassDate::create($payload);
        if ($create) {
            return redirect()->route('master-class-date.index')->with('success', 'Date added successfully.!!');
        }
        return redirect()->back()->with('warning', 'Something went wrong while adding date.!!');
    }

    function edit($id)
    {
        $masterDate = MasterClassDate::findOrFail($id);
        return view('master_date.edit', compact('masterDate'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'year' => 'required',
        ]);
        $masterDate = MasterClassDate::findOrFail($id);

        $exists = MasterClassDate::where('date', $payload['date'])->where('year', $payload['year'])->where('id', '!=', $id)->exists();

        if ($exists) {
            return redirect()->back()->with('warning', 'Date already available.!!');
        }

        if ($masterDate->update($payload)) {
            return redirect()->route('master-class-date.index')->with('success', 'Date updated successfully.!!');
        }

        return redirect()->back()->with('warning', 'Something went wrong while updating date.!!');
    }

    function destroy($id)
    {
        $masterDate = MasterClassDate::findOrFail($id);
        $masterDate->delete();
        return redirect()->back()->with('warning', 'Something went wrong while updating date.!!');
    }

    function addTopic($id)
    {
        $masterDate = MasterClassDate::findOrFail($id);
        return view('master_class_topic.create', compact('masterDate'));
    }
}
