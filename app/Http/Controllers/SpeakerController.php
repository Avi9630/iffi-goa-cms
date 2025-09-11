<?php

namespace App\Http\Controllers;

use App\Models\MasterClassTopic;
use App\Models\Speaker;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    public function __construct()
    {
        $this->destination = env('MASTER_CLASS_DESTINATION');
    }

    function index()
    {
        $speakers = Speaker::all();
        return view('speakers.index', compact('speakers'));
    }
    
    function create()
    {
        $masterTopics = MasterClassTopic::where(['status' => 1])->get();   
        return view('speakers.createNew', compact('masterTopics'));
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'topic_id' => 'required|numeric',
            'speaker_name' => 'required|string',
            'speaker_detail' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $speaker = new Speaker();
        $speaker['topic_id'] = $payload['topic_id'];
        $speaker['speaker_name'] = $payload['speaker_name'];
        $speaker['speaker_detail'] = $payload['speaker_detail'];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
            if ($convertInWebp) {
                $speaker->image_name = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $speaker->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
            }
        }
        if ($speaker->save()) {
            return redirect()->route('speaker.index')->with('success', 'Master class addedd successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during add master class.!!');
        }
    }

    function edit($id)
    {
        $speaker = Speaker::findOrFail($id);
        return view('speakers.edit', compact('speaker'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $request->validate([
            'topic_id' => 'required|numeric',
            'speaker_name' => 'required|string',
            'speaker_detail' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $speaker = Speaker::findOrFail($id);

        $speaker['topic_id'] = $payload['topic_id'];
        $speaker['speaker_name'] = $payload['speaker_name'];
        $speaker['speaker_detail'] = $payload['speaker_detail'];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
            if ($convertInWebp) {
                $speaker->image_name = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $speaker->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
            }
        }

        if ($speaker->save()) {
            return redirect()->route('speaker.index')->with('success', 'Master class addedd successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong during add master class.!!');
        }
    }

    function toggleStatus($id)
    {
        $speaker = Speaker::findOrFail($id);
        $speaker->status = !$speaker->status;
        $speaker->save();
        return redirect()->route('speaker.index')->with('success', 'Status updated successfully.!!');
    }

    function destroy($id)
    {
        $speaker = Speaker::findOrFail($id);
        $speaker->delete();
        return redirect()->back()->with('danger', 'Master class deleted successfully.!!');
    }
}
