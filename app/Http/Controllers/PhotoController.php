<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoCategory;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->bucketName = config('services.gcs.bucket');
        $this->galleryDestination = env('GALLERY');
    }

    function index()
    {
        $photos = Photo::whereNull('video_url')->where('year', 2024)->orderBy('id', 'DESC')->paginate(5);
        $videos = Photo::whereNotNull('video_url')->where('year', 2024)->orderBy('id', 'DESC')->paginate(5);
        return view('photos.index', compact(['photos', 'videos']));
    }

    function create()
    {
        $photoCategories = PhotoCategory::all();
        return view('photos.create', compact('photoCategories'));
    }

    function store(Request $request, GCSService $gcsService)
    {
        $payload = $request->all();
        // $request->validate([
        //     'image' => 'required_without_all:video_url|image|mimes:jpg,jpeg,png,webp|max:2048',
        //     'video_url' => 'required_without_all:image',
        //     'year' => 'required|integer',
        //     'category_id' => 'required',
        //     'caption' => 'required',
        // ]);
        $request->validate([
            'image' => 'required_without_all:video_url',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'required_without_all:image',
            'year' => 'required|integer',
            'category_id' => 'required',
            'caption' => 'required_with:video_url',
        ]);

        if (isset($payload['video_url']) && !empty($payload['video_url'])) {
            if ($payload['category_id'] != 12) {
                return redirect()->back()->with('danger', 'Please select only No Category in the case of Video_url.');
            }
        }

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                if ($file->isValid()) {
                    $originalFilename = $file->getClientOriginalName();
                    $filenameWithoutExt = pathinfo($originalFilename, PATHINFO_FILENAME);
                    $publicUrl = $gcsService->upload($file, 'uploads/gallery/' . $payload['year'] . '/' . time() . '_' . $originalFilename);
                    $photo = new Photo();
                    $photo->year = $payload['year'];
                    $photo->category_id = $payload['category_id'];
                    $photo->img_caption = $filenameWithoutExt;
                    $photo->image = $originalFilename;
                    $photo->img_url = $publicUrl;
                    $photo->video_url = null;
                    $photo->save();
                }
            }
        } else {
            $photo = new Photo();
            $photo->year = $payload['year'];
            $photo->category_id = $payload['category_id'];
            $photo->img_caption =$payload['caption'];
            $photo->video_url = $request->video_url;
            $photo->image = null;
            $photo->img_url = null;
            $photo->save();
        }
        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //     $file = $request->file('image');
        //     $originalFilename = $file->getClientOriginalName();

        //     $publicUrl = $gcsService->upload($file, 'uploads/gallery/' . $payload['year'] . time() . $originalFilename);
        //     $photo->image = $originalFilename;
        //     $photo->img_url = $publicUrl;
        //     $photo->video_url = null;
        // } else {
        //     $photo->video_url = $request->video_url;
        //     $photo->image = null;
        //     $photo->img_url = null;
        // }
        // $photo->save();
        return redirect()->route('photo.index')->with('success', 'Photo uploaded successfully.');
    }

    function edit($id)
    {
        $photo = Photo::findOrFail($id);
        $photoCategories = PhotoCategory::all();
        return view('photos.edit', compact('photo', 'photoCategories'));
    }

    function update(Request $request, GCSService $gcsService, $id)
    {
        $photo = Photo::findOrFail($id);
        $payload = $request->all();
        $request->validate([
            'image' => [$photo->image ? 'nullable' : 'required_without_all:video_url', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'video_url' => [$photo->image ? 'nullable' : 'required_without_all:image'],
            'year' => 'required|integer',
            'category_id' => 'required_with:image',
            'img_caption' => 'required_with:image',
        ]);

        $photo->year = $request->year ?? null;
        $photo->category_id = $request->category_id ?? null;
        $photo->img_caption = $request->img_caption ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($photo->img_url) {
                $gcsService->deleteImageFromGCS($photo->img_url);
            }
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            $publicUrl = $gcsService->upload($file, 'uploads/gallery/' . $payload['year'] . time() . $originalFilename);
            $photo->img_url = $publicUrl;
            $photo->image = $originalFilename;
            $photo->video_url = null;
        } elseif ($request->video_url) {
            $photo->video_url = $request->video_url;
            $photo->img_url = null;
            $photo->image = null;
        }
        $photo->save();
        return redirect()->route('photo.index')->with('success', 'Photo updated successfully.');
    }

    function destroy($id, GCSService $gcsService)
    {
        $photo = Photo::findOrFail($id);
        if (!empty($photo->img_url)) {
            $parsedUrl = parse_url($photo->img_url, PHP_URL_PATH);
            $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
            app(GCSService::class)->deleteImageFromGCS($filePath);
        }
        $photo->delete();
        return redirect()->route('photo.index')->with('danger', 'Photo deleted successfully.!!');
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

    function search(Request $request)
    {
        $payload = $request->all();
        $searchTerm = $request->input('search');

        $photos = Photo::where('video_url', '=', null)->where('year', $searchTerm)->orderBy('id', 'DESC')->paginate(5);

        $videos = Photo::where('video_url', '!=', null)->where('year', $searchTerm)->orderBy('id', 'DESC')->paginate(5);
        return view('photos.index', compact(['photos', 'videos']));
    }
}
