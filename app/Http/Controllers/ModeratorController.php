<?php

namespace App\Http\Controllers;

use App\Models\Moderator;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function __construct()
    {
        $this->destination = env('MASTER_CLASS_DESTINATION');
    }

    function index()
    {
        $moderators = Moderator::all();
        return view('moderators.index', compact('moderators'));
    }

    function create()
    {
        return view('moderators.create');
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'topic_id' => 'required|numeric',
            'moderator_name' => 'required|string',
            'moderator_detail' => 'nullable|string',
            'image' => 'nullable|image|mimes:webp|max:2048',
        ]);

        $moderator = new Moderator();
        $moderator['topic_id'] = $payload['topic_id'];
        $moderator['moderator_name'] = $payload['moderator_name'];
        $moderator['moderator_detail'] = $payload['moderator_detail'] ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $moderator->moderator_image_name = $originalFilename;
            $moderator->moderator_image_url = 'https://www.iffigoa.org/public/images/master-class/' . $originalFilename;
        }

        if ($moderator->save()) {
            return redirect()->route('moderator.index')->with('success', 'Moderator addedd successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during add master class.!!');
        }
    }

    function edit($id)
    {
        $moderator = Moderator::findOrFail($id);
        return view('moderators.edit', compact('moderator'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $request->validate([
            'topic_id' => 'required|numeric',
            'moderator_name' => 'required|string',
            'moderator_detail' => 'nullable|string',
            'image' => 'nullable|image|mimes:webp|max:2048',
        ]);

        $moderator = Moderator::findOrFail($id);

        $moderator['topic_id'] = $payload['topic_id'];
        $moderator['moderator_name'] = $payload['moderator_name'];
        $moderator['moderator_detail'] = $payload['moderator_detail'] ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $moderator->image_name = $originalFilename;
            $moderator->image_url = 'https://www.iffigoa.org/public/images/master-class/' . $originalFilename;
        }

        if ($moderator->save()) {
            return redirect()->route('moderator.index')->with('success', 'Master class addedd successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during add master class.!!');
        }
    }

    function toggleStatus($id)
    {
        $moderator = Moderator::findOrFail($id);
        $moderator->status = !$moderator->status;
        $moderator->save();
        return redirect()->route('moderator.index')->with('success', 'Status updated successfully.!!');
    }

    function destroy($id)
    {
        $moderator = Moderator::findOrFail($id);
        $moderator->delete();
        return redirect()->back()->with('danger', 'Moderator deleted successfully.!!');
    }
}
