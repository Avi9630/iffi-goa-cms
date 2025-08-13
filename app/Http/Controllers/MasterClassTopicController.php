<?php

namespace App\Http\Controllers;

use App\Models\MasterClassTopic;
use Illuminate\Http\Request;

class MasterClassTopicController extends Controller
{
    function index()
    {
        $masterClassesTpic = MasterClassTopic::all();
        return view('master_class_topic.index', compact('masterClassesTpic'));
    }

    function create()
    {
        return view('master_class_topic.create');
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'master_date_id' => 'required|numeric',
        ]);

        $masterClass = new MasterClassTopic();
        $masterClass['title'] = $payload['title'];
        $masterClass['description'] = $payload['description'];
        $masterClass['master_date_id'] = $payload['master_date_id'];

        if ($masterClass->save()) {
            return redirect()->route('master-class-topic.index')->with('success', 'Master class addedd successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during add master class.!!');
        }
    }

    function edit($id)
    {
        $masterClass = MasterClassTopic::findOrFail($id);
        return view('master_class_topic.edit', compact('masterClass'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $masterClass = MasterClassTopic::findOrFail($id);
        $masterClass['title'] = $payload['title'];
        $masterClass['description'] = $payload['description'];
        if ($masterClass->save()) {
            return redirect()->route('master-class-topic.index')->with('success', 'Master class Updated successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during update master class.!!');
        }
    }

    function toggleStatus($id)
    {
        $masterClass = MasterClassTopic::findOrFail($id);
        $masterClass->status = !$masterClass->status;
        $masterClass->save();
        return redirect()->route('master-class-topic.index')->with('success', 'Status updated successfully.!!');
    }

    function destroy($id)
    {
        $masterClass = MasterClassTopic::findOrFail($id);
        $masterClass->delete();
        return redirect()->back()->with('danger', 'Master class deleted successfully.!!');
    }

    function addDetail ($id) 
    {
        $masterTopic = MasterClassTopic::findOrFail($id);
        return view('master_class.create',compact('masterTopic'));
    }

    function addSpeaker ($id) 
    {
        $masterTopic = MasterClassTopic::findOrFail($id);
        return view('speakers.create',compact('masterTopic'));
    }

    function addModerator ($id) 
    {
        $masterTopic = MasterClassTopic::findOrFail($id);
        return view('moderators.create',compact('masterTopic'));
    }
}
