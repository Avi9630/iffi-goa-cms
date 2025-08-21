<?php

namespace App\Http\Controllers;

use App\Models\Peacock;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;

class PeacockController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->bucketName = config('services.gcs.bucket');
        $this->posterDestination = env('PEACOCK_POSTER_DESTINATION');
        $this->PDFDestination = env('PEACOCK_PDF_DESTINATION');
    }

    function index()
    {
        $peacocks = Peacock::orderBy('id', 'DESC')->paginate(10);
        return view('peacock.index', compact('peacocks'));
    }

    function toggleStatus($id)
    {
        $peacock = Peacock::findOrFail($id);
        $peacock->status = $peacock->status === 1 ? 0 : 1;
        $peacock->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function create()
    {
        return view('peacock.create');
    }

    function store(Request $request, GCSService $gcsService)
    {
        $payload = $request->all();
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|mimes:pdf',
            'poster' => 'required|image|mimes:webp,|max:2048',
            'year' => 'required|integer',
        ]);

        $peacock = new Peacock();
        $peacock->title = $payload['title'] ?? null;
        $peacock->year = $payload['year'] ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->PDFDestination);
            $peacock->img_src = $originalFilename;
            $peacock->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->PDFDestination . '/' . $originalFilename;
            $peacock->image_name = $originalFilename;
        }

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            $file = $request->file('poster');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->posterDestination);
            $peacock->poster = $originalFilename;
            $peacock->poster_url = env('IMAGE_UPLOAD_BASE_URL') . $this->posterDestination . '/' . $originalFilename;
        }
        $peacock->save();
        return redirect()->route('peacock.index')->with('success', 'Press Release created successfully.');
    }

    function edit($id)
    {
        $peacock = Peacock::findOrFail($id);
        return view('peacock.edit', compact('peacock'));
    }

    function update(Request $request, $id, GCSService $gcsService)
    {
        $payload = $request->all();
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|mimes:pdf',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'year' => 'nullable|integer',
        ]);

        $peacock = Peacock::findOrFail($id);
        $peacock->title = $request->title ?? $peacock->title;
        $peacock->year = $request->year ?? $peacock->year;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->PDFDestination);
            $peacock->img_src = $originalFilename;
            $peacock->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->PDFDestination . '/' . $originalFilename;
            $peacock->image_name = $originalFilename;
        }

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            $file = $request->file('poster');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->posterDestination);
            $peacock->poster = $originalFilename;
            $peacock->poster_url = env('IMAGE_UPLOAD_BASE_URL') . $this->posterDestination . '/' . $originalFilename;
        }

        $peacock->save();
        return redirect()->route('peacock.index')->with('success', 'Press Release updated successfully.');
    }

    function destroy($id)
    {
        $peacock = Peacock::findOrFail($id);
        // if (!empty($peacock->image_url)) {
        //     $parsedUrl = parse_url($peacock->image_url, PHP_URL_PATH);
        //     $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
        //     app(GCSService::class)->deleteImageFromGCS($filePath);
        // }
        // if (!empty($peacock->poster_url)) {
        //     $parsedUrl = parse_url($peacock->poster_url, PHP_URL_PATH);
        //     $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
        //     app(GCSService::class)->deleteImageFromGCS($filePath);
        // }
        $peacock->delete();
        return redirect()->route('peacock.index')->with('danger', 'Peacock deleted successfully.!!');
    }
}
