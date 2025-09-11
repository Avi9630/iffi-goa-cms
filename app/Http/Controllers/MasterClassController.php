<?php

namespace App\Http\Controllers;

use App\Models\MasterClassTopic;
use Illuminate\Http\Request;
use App\Models\MasterClass;

class MasterClassController extends Controller
{
    function index()
    {
        $masterClasses = MasterClass::all();
        return view('master_class.index', compact('masterClasses'));
    }

    function create()
    {
        $masterTopics = MasterClassTopic::where(['status' => 1])->get();
        return view('master_class.createNew', compact('masterTopics'));
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'topic_id' => 'required|numeric',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'format' => 'nullable|string',
            'session_url' => 'nullable|string',
        ]);
        $masterClass = new MasterClass();
        $masterClass['topic_id'] = $payload['topic_id'];
        $masterClass['start_time'] = $payload['start_time'];
        $masterClass['end_time'] = $payload['end_time'];
        $masterClass['format'] = $payload['format']?? null;
        $masterClass['session_url'] = $payload['session_url']?? null;

        if ($masterClass->save()) {
            return redirect()->route('master-class.index')->with('success', 'Master class addedd successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during add master class.!!');
        }
    }

    function edit($id)
    {
        $masterClass = MasterClass::findOrFail($id);
        return view('master_class.edit', compact('masterClass'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();

        $request->validate([
            'topic_id' => 'required|numeric',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'year' => 'nullable|digits:4|integer|min:2022|max:2025',
            'format' => 'nullable|string',
            'session_url' => 'nullable',
        ]);
        $masterClass = MasterClass::findOrFail($id);
        $masterClass['start_time'] = $payload['start_time'];
        $masterClass['end_time'] = $payload['end_time'];
        $masterClass['format'] = $payload['format'] ?? null;
        $masterClass['session_url'] = $payload['session_url'] ?? null;
        if ($masterClass->save()) {
            return redirect()->route('master-class.index')->with('success', 'Master class Updated successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during update master class.!!');
        }
    }

    function toggleStatus($id)
    {
        $masterClass = MasterClass::findOrFail($id);
        $masterClass->status = !$masterClass->status;
        $masterClass->save();
        return redirect()->route('master-class.index')->with('success', 'Status updated successfully.!!');
    }

    function destroy($id)
    {
        $masterClass = MasterClass::findOrFail($id);
        $masterClass->delete();
        return redirect()->back()->with('danger', 'Master class deleted successfully.!!');
    }
}
