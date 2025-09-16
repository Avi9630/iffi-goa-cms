<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\CONSTTrait;
use App\Services\ExternalApiService;
use App\Services\ConvertToWEBP;
use App\Services\GCSService;
use Illuminate\Http\Request;
use App\Models\NewsUpdate;

class NewsUpdateController extends Controller
{
    use CONSTTrait;

    protected $bucketName;

    public function __construct()
    {
        $this->destination = env('NEWS_AND_UPDATE');
    }

    function index()
    {
        $newsUpdates = NewsUpdate::orderBy('id', 'DESC')->paginate(10);
        return view('news_update.index', compact('newsUpdates'));
    }

    function newsSearch(Request $request)
    {
        $payload = $request->all();
        $searchTerm = $request->input('search');
        $newsUpdates = NewsUpdate::where('title', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description', 'LIKE', "%{$searchTerm}%")
            ->orderBy('id', 'DESC')
            ->paginate(10);
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
            // 'sort_num' => 'nullable|integer',
            'image_name' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['pop_up_header', 'pop_up_content', 'image_name']);

        $newsUpdate = NewsUpdate::findOrFail($id);
        $newsUpdate->update($data);

        return redirect()->route('news-update.index')->with('success', 'Popup updated successfully.');
    }

    function create()
    {
        return view('news_update.create');
    }

    function store(Request $request, ConvertToWEBP $webp)
    {
        $payload = $request->all();
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required_without:image_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url'   => 'required_without:image|nullable|string|max:255',
            'link'        => 'nullable|max:255',
            'link_title'  => 'nullable|string|max:255',
            'have_popup'  => 'required|in:0,1',
            'sort_num'    => 'nullable|integer',
        ]);

        $newsUpdate = new NewsUpdate();

        $newsUpdate->title = $payload['title'];
        $newsUpdate->description = $payload['description'];
        $newsUpdate->link = $payload['link'] ?? null;
        $newsUpdate->link_title = $payload['link_title'] ?? null;
        $newsUpdate->have_popup = $payload['have_popup'];
        $newsUpdate->sort_num = $payload['sort_num'] ?? null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
            if ($convertInWebp) {
                $newsUpdate->image_name = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $newsUpdate->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                // $newsUpdate->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $newsUpdate->image_url = null;
            }
        } else {
            if ($request->filled('image_url') && !filter_var($request->image_url, FILTER_VALIDATE_URL)) {
                $newsUpdate->image_url = $payload['image_url'];
                $newsUpdate->image_name = null;
                $newsUpdate->img_src = null;
            }
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
            'image'       => 'required_without:image_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url'   => 'required_without:image|nullable|string|max:255',
            'link' => 'nullable|max:255',
            'link_title' => 'nullable|string|max:255',
            'have_popup' => 'required|in:0,1',
            'sort_num' => '',
        ]);

        $newsUpdate = NewsUpdate::findOrFail($id);
        $newsUpdate['title'] = $payload['title'];
        $newsUpdate['description'] = $payload['description'];
        $newsUpdate['link'] = $payload['link'] ?? $payload['link'];
        $newsUpdate['link_title'] = $payload['link_title'] ?? $payload['link_title'];
        $newsUpdate['have_popup'] = $payload['have_popup'];
        $newsUpdate['sort_num'] = $payload['sort_num']  ?? null;

        if ($request->hasFile('image')) {
            if (!is_null($newsUpdate->image_url)) {
                $parsedUrl = parse_url($newsUpdate->image_url, PHP_URL_PATH);
                $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
                $gcsService->deleteImageFromGCS($filePath);
            }
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
            if ($convertInWebp) {
                $newsUpdate->image_name = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $newsUpdate->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $newsUpdate->image_url = null;
            }
        } else {
            $newsUpdate->image_url = $payload['image_url'];
            $newsUpdate->image_name = null;
            $newsUpdate->img_src = null;
        }
        if ($newsUpdate->save()) {
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

    public function popupImage()
    {
        $locations = $this->locations();
        $response = app(ExternalApiService::class)->getImageList($this->destination);
        if (!empty($response['error'])) {
            return back()->withErrors(['msg' => $response['message']]);
        }
        $images = $response['files'] ?? [];
        return view('news_update.image', compact(['images', 'locations']));
    }

    function popupImageUpload(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:2048',
        ]);
        $location = $payload['location'] ?? '';

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            if(isset($payload['location']) && !empty($payload['location'])){
                $location = $payload['location'];
            }else {
                $location = $this->destination;
            }
            app(ExternalApiService::class)->postData($file, $location);
            app(ConvertToWEBP::class)->convert($file, $location);
        }
        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }
}
