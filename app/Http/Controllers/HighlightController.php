<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;

class HighlightController extends Controller
{

    function __construct()
    {
        $this->destination = env('HIGHLIGHT_DESTINATION');
    }
    function index()
    {
        $highlights = Highlight::orderBy('id','DESC')->paginate(10);
        return view('highlights.index', compact('highlights'));
    }

    function create()
    {
        return view('highlights.create');
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'image' => 'required_without:image_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'required_without:image|nullable|string|max:255',
        ]);

        $highlight = new Highlight();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $upload = app(ExternalApiService::class)->postData($file, $this->destination);
            if (!$upload['status']) {
                return redirect()->back()->with('error', 'Failed to upload image to external service. Please try again.!!');
            }
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
            if ($convertInWebp) {
                $highlight->img_src = $extension === 'webp' ? $upload['data']['fileName'] : $convertInWebp;
                $highlight->img_url = null;
            }
        } else {
            $highlight->img_url = $payload['image_url'];
            $highlight->img_src = null;
        }

        if ($highlight->save()) {
            return redirect()->route('highlight.index')->with('success', 'Highlight uploaded successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create. Please try again.!!');
        }
    }

    function edit($id)
    {
        $highlight = Highlight::findOrFail($id);
        return view('highlights.edit', compact('highlight'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
       $request->validate([
            'image'     =>  'required_without:image_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' =>  'required_without:image|nullable|string|max:255',
        ]);

        $highlight = Highlight::findOrFail($id);

        if ($highlight) {
           
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $extension = strtolower($file->getClientOriginalExtension());
                $upload = app(ExternalApiService::class)->postData($file, $this->destination);
                if (!$upload['status']) {
                    return redirect()->back()->with('error', 'Failed to upload image to external service. Please try again.!!');
                }
                $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
                if ($convertInWebp) {
                    $highlight->img_src = $extension === 'webp' ? $upload['data']['fileName'] : $convertInWebp;
                    $highlight->img_url = null;
                }
            } else {
                $highlight->img_url = $payload['image_url'];
                $highlight->img_src = null;
            }

            if ($highlight->save()) {
                return redirect()->route('highlight.index')->with('success', 'Cube uploaded successfully.!!');
            } else {
                return redirect()->back()->with('error', 'Failed to create. Please try again.!!');
            }
        } else {
            return redirect()->route('highlight.index')->with('warning', 'Something went wrong.!!');
        }
    }

    function destroy($id)
    {
        $highlight = Highlight::findOrFail($id);
        // if (!empty($highlight->img_url)) {
        //     $parsedUrl = parse_url($highlight->img_url, PHP_URL_PATH);
        //     $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
        //     app(GCSService::class)->deleteImageFromGCS($filePath);
        // }
        $highlight->delete();
        return redirect()->route('highlight.index')->with('danger', 'Cube deleted successfully.!!');
    }

    function toggleStatus($id)
    {
        $highlight = Highlight::findOrFail($id);
        $highlight->status = !$highlight->status;
        $highlight->save();
        return redirect()->back()->with('success', 'Highlight status updated successfully.!!');
        // return redirect()->route('highlight.index')->with('success', 'Highlight status updated successfully.!!');
    }
}
