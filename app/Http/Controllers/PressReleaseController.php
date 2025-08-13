<?php

namespace App\Http\Controllers;

use App\Models\PressRelease;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;

class PressReleaseController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->bucketName = config('services.gcs.bucket');
        $this->destination = env('PRESS_RELEASE');
    }

    function index()
    {
        $pressReleases = PressRelease::orderBy('id', 'DESC')->paginate(10);
        return view('press_release.index', compact('pressReleases'));
    }

    function toggleStatus($id)
    {
        $ticker = PressRelease::findOrFail($id);
        $ticker->status = $ticker->status === 1 ? 0 : 1;
        $ticker->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function create()
    {
        return view('press_release.create');
    }

    function store(Request $request, GCSService $gcsService)
    {
        $payload = $request->all();
        $request->validate([
            'title' => 'required|string|max:255',
            'publish_date' => 'required|date',
            'description' => 'nullable|string',
            'image' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:2048|required_without:link',
            // 'link' => 'nullable|required_without:image',
        ]);

        $pressRelease = new PressRelease();
        $pressRelease->title = $request->title ?? null;
        $pressRelease->publish_date = $request->publish_date;
        $pressRelease->description = $request->description ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            // Upload to GCS using service
            // $publicUrl = $gcsService->upload($file, 'uploads/pressRelease/' . time() . $originalFilename);
            // $pressRelease->image_url = $publicUrl;
            app(ExternalApiService::class)->postData($file, $this->destination);
            $pressRelease->img_src = $originalFilename;
            $pressRelease->link = 'https://www.iffigoa.org/public/press_release/' . $originalFilename;
            $pressRelease->save();
            return redirect()->route('press-release.index')->with('success', 'Press Release created successfully.');
        }else {
            return redirect()->route('press-release.index')->with('warning', 'File must be uploaded.!!');
        }
    }

    function edit($id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        return view('press_release.edit', compact('pressRelease'));
    }

    function update(Request $request, $id, GCSService $gcsService)
    {
        $pressRelease = PressRelease::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'publish_date' => 'required|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|required_without:link',
            // 'link' => 'nullable|required_without:image',
        ]);

        $pressRelease->title = $request->title ?? null;
        $pressRelease->publish_date = $request->publish_date;
        $pressRelease->description = $request->description ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // if ($pressRelease->image_url) {
            //     $parsedUrl = parse_url($pressRelease->image_url, PHP_URL_PATH);
            //     $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
            //     $gcsService->deleteImageFromGCS($filePath);
            // }
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            // Upload to GCS using service
            // $publicUrl = $gcsService->upload($file, 'uploads/pressRelease/' . time() . $originalFilename);
            // $pressRelease->image_url = $publicUrl ?? null;
            // $pressRelease->link = null;
            app(ExternalApiService::class)->postData($file, $this->destination);
            $pressRelease->img_src = $originalFilename;
            $pressRelease->link = 'https://www.iffigoa.org/public/press_release/' . $originalFilename;
        } 
        // else {
        //     if ($pressRelease->image_url) {
        //         $parsedUrl = parse_url($pressRelease->image_url, PHP_URL_PATH);
        //         $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
        //         $gcsService->deleteImageFromGCS($filePath);
        //     }
        //     $pressRelease->link = $request->link;
        //     $pressRelease->image_url = null;
        // }
        $pressRelease->save();
        return redirect()->route('press-release.index')->with('success', 'Press Release updated successfully.');
    }

    function destroy($id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        // if (!empty($pressRelease->image_url)) {
        //     $parsedUrl = parse_url($pressRelease->image_url, PHP_URL_PATH);
        //     $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
        //     app(GCSService::class)->deleteImageFromGCS($filePath);
        // }
        $pressRelease->delete();
        return redirect()->route('press-release.index')->with('danger', 'Press Release deleted successfully.!!');
    }
}
