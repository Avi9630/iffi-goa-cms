<?php

namespace App\Http\Controllers;

use App\Models\Peacock;
use App\Services\GCSService;
use Illuminate\Http\Request;

class PeacockController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->bucketName = config('services.gcs.bucket');
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
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|mimes:pdf',
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $peacock = new Peacock();
        $peacock->title = $request->title ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            $imageUrl = $gcsService->upload($file, 'uploads/peacock/' . time() . $originalFilename);
            $peacock->image_url = $imageUrl;
            $peacock->image_name = $originalFilename;
        }

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            $file = $request->file('poster');
            $originalFilename = $file->getClientOriginalName();
            $posterUrl = $gcsService->upload($file, 'uploads/peacock/' . time() . $originalFilename);
            $peacock->poster_url = $posterUrl;
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
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|mimes:pdf',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $peacock = Peacock::findOrFail($id);
        $peacock->title = $request->title ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($peacock->image_url) {
                $parsedUrl = parse_url($peacock->image_url, PHP_URL_PATH);
                $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
                $gcsService->deleteImageFromGCS($filePath);
            }
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            // Upload to GCS using service
            $imageUrl = $gcsService->upload($file, 'uploads/peacock/' . time() . $originalFilename);
            
            $peacock->image_url = $imageUrl ?? null;
            $peacock->image_name = $originalFilename;
        }

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            if ($peacock->poster_url) {
                $parsedUrl = parse_url($peacock->poster_url, PHP_URL_PATH);
                $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
                $gcsService->deleteImageFromGCS($filePath);
            }
            $file = $request->file('poster');
            $originalFilename = $file->getClientOriginalName();
            // Upload to GCS using service
            $posterUrl = $gcsService->upload($file, 'uploads/peacock/' . time() . $originalFilename);
            $peacock->poster_url = $posterUrl ?? null;
        }
        $peacock->save();
        return redirect()->route('peacock.index')->with('success', 'Press Release updated successfully.');
    }

    function destroy($id)
    {
        $peacock = Peacock::findOrFail($id);
        if (!empty($peacock->image_url)) {
            $parsedUrl = parse_url($peacock->image_url, PHP_URL_PATH);
            $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
            app(GCSService::class)->deleteImageFromGCS($filePath);
        }
        if (!empty($peacock->poster_url)) {
            $parsedUrl = parse_url($peacock->poster_url, PHP_URL_PATH);
            $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
            app(GCSService::class)->deleteImageFromGCS($filePath);
        }
        $peacock->delete();
        return redirect()->route('peacock.index')->with('danger', 'Press Release deleted successfully.!!');
    }
}
