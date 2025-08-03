<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoCategory;
use App\Services\GCSService;
use Illuminate\Http\Request;

class PhotoController extends Controller
{

    protected $bucketName;

    public function __construct()
    {
        $this->bucketName = config('services.gcs.bucket');
    }
    
    function index()
    {
        $photos = Photo::where('year', 2025)->paginate(10);
        return view('photos.index', compact('photos'));
    }

    function create()
    {
        $photoCategories = PhotoCategory::all();
        return view('photos.create', compact('photoCategories'));
    }

    function store(Request $request, GCSService $gcsService)
    {
        $payload = $request->all();
        $request->validate([
            'category_id' => 'required',
            'image' => 'required_without:video_url|image|mimes:jpg,jpeg,png,gif|max:2048',
            'video_url' => 'required_without:image',
            'year' => 'required|integer',
        ]);
        $photo = new Photo();
        $photo->year = $request->year ?? null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            // Upload to GCS using service
            $publicUrl = $gcsService->upload($file, 'uploads/gallery/' . $payload['year'] . time() . $originalFilename);
            $photo->image_url = $publicUrl;
        } else {
            $photo->video_url = $request->video_url;
        }
        $photo->save();
        return redirect()->route('photos.index')->with('success', 'Photo uploaded successfully.');
    }

    function edit($id)
    {
        $photo = Photo::findOrFail($id);
        $photoCategories = PhotoCategory::all();
        return view('photos.edit', compact('photo', 'photoCategories'));
    }

    function update(Request $request, GCSService $gcsService, $id)
    {
        $payload = $request->all();
        $request->validate([
            'category_id' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'video_url' => 'nullable',
            'year' => 'required|integer',
        ]);
        $photo = Photo::findOrFail($id);
        $photo->year = $request->year ?? null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($photo->image_url) {
                // Delete old image from GCS
                $gcsService->deleteImageFromGCS($photo->image_url);
            }
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            // Upload new image to GCS
            $publicUrl = $gcsService->upload($file, 'uploads/gallery/' . $payload['year'] . time() . $originalFilename);
            $photo->image_url = $publicUrl;
        } elseif ($request->video_url) {
            $photo->video_url = $request->video_url;
        }
        $photo->save();
        return redirect()->route('photos.index')->with('success', 'Photo updated successfully.');
    }

    function destroy($id, GCSService $gcsService)
    {
        $photo = Photo::findOrFail($id);
        if (!empty($photo->image_url)) {
            $parsedUrl = parse_url($photo->image_url, PHP_URL_PATH);
            $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
            app(GCSService::class)->deleteImageFromGCS($filePath);
        }
        $photo->delete();
        return redirect()->route('photos.index')->with('danger', 'Photo deleted successfully.!!');
    
    }

    function toggleStatus($id)
    {
        $photo = Photo::findOrFail($id);
        $photo->status = !$photo->status;
        $photo->save();
        return redirect()->route('photo.index')->with('success', 'Photo status updated successfully.');
    }

    function highlightToggle($id)
    {
        $photo = Photo::findOrFail($id);
        $photo->highlights = !$photo->highlights;
        $photo->save();
        return redirect()->route('photo.index')->with('success', 'Photo highlight status updated successfully.');
    }

    function activeToggle($id)
    {
        $photo = Photo::findOrFail($id);
        $photo->is_active = !$photo->is_active;
        $photo->save();
        return redirect()->route('photo.index')->with('success', 'Photo active status updated successfully.');
    }
}
