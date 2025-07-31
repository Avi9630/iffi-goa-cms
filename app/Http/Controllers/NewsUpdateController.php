<?php

namespace App\Http\Controllers;

use Google\Cloud\Storage\StorageClient;
use App\Http\Controllers\Controller;
use App\Http\Traits\RESPONSETrait;
use App\Models\PhotoCategory;
use Illuminate\Http\Request;
use App\Models\NewsUpdate;
use App\Services\GCSService;
use GuzzleHttp\Client;

class NewsUpdateController extends Controller
{
    // public function __construct()
    // {
    //     $this->projectId = env('GOOGLE_CLOUD_PROJECT_ID');
    //     $this->bucketName = env('GOOGLE_CLOUD_STORAGE_BUCKET');
    //     $this->keyFilePath = storage_path('app/keys/' . env('GOOGLE_APPLICATION_CREDENTIALS'));
    //     $this->gcsApi = env('GOOGLE_CLOUD_STORAGE_API_URI');
    // }

    function index()
    {
        $newsUpdates = NewsUpdate::orderBy('id', 'DESC')->get();
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
        $publicUrl = $gcsService->upload($file, 'newsUpdate/' . $originalFilename);

        // OLD CODE - IGNORE BUT KEEP FOR REFERENCE

        // //This is for GOOGLE CLOUD STORAGE
        // $guzzleClient = new Client([
        //     'verify' => false,
        //     'curl' => [
        //         CURLOPT_SSL_VERIFYPEER => false,
        //         CURLOPT_SSL_VERIFYHOST => false,
        //         CURLOPT_CAINFO => 'C:\php\extras\ssl\cacert.pem',
        //     ],
        // ]);
        // $storage = new StorageClient([
        //     'projectId' => $this->projectId,
        //     'keyFilePath' => $this->keyFilePath,
        //     'httpClient' => $guzzleClient,
        // ]);
        // $bucket = $storage->bucket($this->bucketName);
        // $bucket->upload(fopen($tempPath, 'r'), ['name' => 'newsUpdate/' . $originalFilename]);
        // $publicUrl = sprintf($this->gcsApi, $this->bucketName, $originalFilename);

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

        // Validate the image file
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            // Upload to GCS using service
            $publicUrl = $gcsService->upload($file, 'uploads/newsUpdate/' . $originalFilename);
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

    function destroy($id)
    {
        $newsUpdate = NewsUpdate::findOrFail($id);
        $newsUpdate->delete();
        return redirect()->route('news-update.index')->with('success', 'News Update deleted successfully.');
    }
}
