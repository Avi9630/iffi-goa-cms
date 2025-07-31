<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\GCSService;
use Illuminate\Http\Request;
use App\Models\NewsUpdate;

class NewsUpdateController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->projectId = config('services.gcs.project_id');
        $this->keyFilePath = config('services.gcs.key_file');
        $this->bucketName = config('services.gcs.bucket');
        $this->publicUrlFormat = config('services.gcs.public_url_format');
    }

    function index()
    {
        $newsUpdates = NewsUpdate::orderBy('id', 'DESC')->paginate(10);
        return view('news_update.index', compact('newsUpdates'));
    }

    function toggleStatus($id)
    {
        $newsUpdate = NewsUpdate::findOrFail($id);
        $newsUpdate->status = $newsUpdate->status === 1 ? 0 : 1;
        $newsUpdate->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function popupToggle($id)
    {
        $newsUpdate = NewsUpdate::findOrFail($id);
        return view('news_update.popup', compact(['newsUpdate']));
    }

    function popupUpdate(Request $request, $id)
    {
        $request->validate([
            'pop_up_header' => 'nullable|string|max:255',
            'pop_up_content' => 'nullable|string',
            'sort_num' => 'nullable|integer',
            'image_name' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['pop_up_header', 'pop_up_content', 'sort_num', 'image_name']);

        $newsUpdate = NewsUpdate::findOrFail($id);
        $newsUpdate->update($data);

        return redirect()->route('news-update.index')->with('success', 'Popup updated successfully.');
    }

    function create()
    {
        return view('news_update.create');
    }

    function store(Request $request, GCSService $gcsService)
    {
        $payload = $request->all();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'link' => 'nullable|url|max:255',
            'link_title' => 'nullable|string|max:255',
            'have_popup' => 'required|in:0,1',
        ]);
        // Validate the image file
        if (!$request->hasFile('image')) {
            return redirect()->back()->with('warning', 'Image file is required.');
        }

        $file = $request->file('image');
        $originalFilename = $file->getClientOriginalName();
        // Upload to GCS using service
        $publicUrl = $gcsService->upload($file, 'uploads/newsUpdate/' . time() . $originalFilename);

        $data = [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'image_url' => $publicUrl,
            'link' => $payload['link'] ?? null,
            'link_title' => $payload['link_title'] ?? null,
            'have_popup' => $payload['have_popup'],
        ];
        $newsUpdate = NewsUpdate::create($data);
        if ($newsUpdate) {
            return redirect()->route('news-update.index')->with('success', 'News Update created successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create News Update. Please try again.');
        }
    }

    function edit($id)
    {
        $newsUpdate = NewsUpdate::findOrFail($id);
        return view('news_update.edit', compact('newsUpdate'));
    }

    function update(Request $request, $id, GCSService $gcsService)
    {
        $payload = $request->all();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'link' => 'nullable|url|max:255',
            'link_title' => 'nullable|string|max:255',
            'have_popup' => 'required|in:0,1',
        ]);

        $newsUpdate = NewsUpdate::findOrFail($id);

        if ($request->hasFile('image')) {
            if (!is_null($newsUpdate->image_url)) {
                $parsedUrl = parse_url($newsUpdate->image_url, PHP_URL_PATH);
                $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
                $gcsService->deleteImageFromGCS($filePath);
            }
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            // Upload to GCS using service
            $publicUrl = $gcsService->upload($file, 'uploads/newsUpdate/' . time() . $originalFilename);
        }

        $newsUpdate = NewsUpdate::findOrFail($id);

        $data = [
            'title' => $payload['title'] ?? $newsUpdate->title,
            'description' => $payload['description'] ?? $newsUpdate->description,
            'image_url' => $publicUrl ?? $newsUpdate->image_url,
            'link' => $payload['link'] ?? $newsUpdate->link,
            'link_title' => $payload['link_title'] ?? $newsUpdate->link_title,
            'have_popup' => $payload['have_popup'] ?? $newsUpdate->have_popup,
        ];

        $newsUpdate = $newsUpdate->update($data);

        if ($newsUpdate) {
            return redirect()->route('news-update.index')->with('success', 'News Update created successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create News Update. Please try again.');
        }
    }

    public function destroy($id, GCSService $gcsService)
    {
        $newsUpdate = NewsUpdate::findOrFail($id);
        $parsedUrl = parse_url($newsUpdate->image_url, PHP_URL_PATH);
        $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
        $gcsService->deleteImageFromGCS($filePath);
        $newsUpdate->delete();
        return redirect()->route('news-update.index')->with('success', 'News update and image deleted.');
    }

    public function popupImage(GCSService $gcsService)
    {
        $images = $gcsService->listImagesFromGCS('uploads/newsUpdate/');
        return view('news_update.image', compact('images'));
    }

    function popupImageUpload(Request $request, GCSService $gcsService)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);
        $file = $request->file('image');
        $originalFilename = $file->getClientOriginalName();
        // Upload to GCS using service
        $gcsService->upload($file, 'uploads/newsUpdate/' . time() . $originalFilename);
        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }
}
