<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;
use App\Models\NewsUpdate;

class NewsUpdateController extends Controller
{
    protected $bucketName;

    public function __construct(ExternalApiService $api)
    {
        $this->projectId = config('services.gcs.project_id');
        $this->keyFilePath = config('services.gcs.key_file');
        $this->bucketName = config('services.gcs.bucket');
        $this->publicUrlFormat = config('services.gcs.public_url_format');
        $this->destination = env('NEWS_AND_UPDATE');
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
            'image' => 'required|file|mimes:webp|max:2048',
            'link' => 'nullable|url|max:255',
            'link_title' => 'nullable|string|max:255',
            'have_popup' => 'required|in:0,1',
        ]);

        $newsUpdate = new NewsUpdate();

        $newsUpdate->title = $payload['title'];
        $newsUpdate->description = $payload['description'];
        $newsUpdate->link = $payload['link'] ?? null;
        $newsUpdate->link_title = $payload['link_title'] ?? null;
        $newsUpdate->have_popup = $payload['have_popup'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $newsUpdate->image_name = $originalFilename;
            $newsUpdate->img_src = $originalFilename;
            $newsUpdate->image_url = 'https://www.iffigoa.org/public/images/news-update/webp/' . $originalFilename;
        }
        if ($newsUpdate->save()) {
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
            'image' => 'nullable|file|mimes:webp|max:2048',
            'link' => 'nullable|url|max:255',
            'link_title' => 'nullable|string|max:255',
            'have_popup' => 'required|in:0,1',
        ]);

        $newsUpdate = NewsUpdate::findOrFail($id);
        $newsUpdate['title'] = $payload['title'];
        $newsUpdate['description'] = $payload['description'];
        $newsUpdate['link'] = $payload['link'] ?? null;
        $newsUpdate['link_title'] = $payload['link_title'] ?? null;
        $newsUpdate['have_popup'] = $payload['have_popup'];

        if ($request->hasFile('image')) {
            if (!is_null($newsUpdate->image_url)) {
                $parsedUrl = parse_url($newsUpdate->image_url, PHP_URL_PATH);
                $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
                $gcsService->deleteImageFromGCS($filePath);
            }
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $newsUpdate->image_name = $originalFilename;
            $newsUpdate->img_src = $originalFilename;
            $newsUpdate->image_url = 'https://www.iffigoa.org/public/images/news-update/webp/' . $originalFilename;
        }
        $newsUpdate = $newsUpdate->save();
        if ($newsUpdate) {
            return redirect()->route('news-update.index')->with('success', 'News Update created successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create News Update. Please try again.');
        }
    }

    public function destroy($id)
    {
        $newsUpdate = NewsUpdate::findOrFail($id);
        $newsUpdate->delete();
        return redirect()->route('news-update.index')->with('success', 'News update and image deleted.');
    }

    // public function popupImage(GCSService $gcsService)
    // {
    //     $response = app(ExternalApiService::class)->getImageList($this->destination);
    //     $images = $response['files'];
    //     return view('news_update.image', compact('images'));
    // }

    public function popupImage(GCSService $gcsService)
    {
        $response = app(ExternalApiService::class)->getImageList($this->destination);

        if (!empty($response['error'])) {
            return back()->withErrors(['msg' => $response['message']]);
        }

        $images = $response['files'] ?? [];
        return view('news_update.image', compact('images'));
    }

    function popupImageUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:webp|max:2048',
        ]);
        $file = $request->file('image');
        $originalFilename = $file->getClientOriginalName();
        app(ExternalApiService::class)->postData($file, $this->destination);
        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }

    function search(Request $request)
    {
        $payload = $request->all();
        $searchTerm = $request->input('search');
        $newsUpdates = NewsUpdate::where('title', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description', 'LIKE', "%{$searchTerm}%")
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('news_update.index', compact('newsUpdates'));
    }
}
