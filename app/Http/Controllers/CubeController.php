<?php

namespace App\Http\Controllers;

use App\Models\Cube;
use App\Services\GCSService;
use Illuminate\Http\Request;

class CubeController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->bucketName = config('services.gcs.bucket');
    }

    function index()
    {
        $cubes = Cube::all();
        return view('cubes.index', compact('cubes'));
    }

    function create()
    {
        return view('cubes.create');
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'required|string',
        ]);

        $cube = new Cube();
        $cube->link = $payload['link'];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            //UPLOAD TO GCS USING SERVICE
            $publicUrl = app(GCSService::class)->upload($file, 'uploads/cubes/' . time() . $originalFilename);
            $cube->image_name = $originalFilename;
            $cube->image_url = $publicUrl;
        }
        $cube->save();
        return redirect()->route('cube.index')->with('success', 'Cube uploaded successfully.!!');
    }

    function edit($id)
    {
        $cube = Cube::findOrFail($id);
        return view('cubes.edit', compact('cube'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'required|string',
        ]);

        $cube = Cube::findOrFail($id);

        if ($cube) {
            $cube->link = $payload['link'] ?? null;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if (!empty($cube->image_url)) {
                    $parsedUrl = parse_url($cube->image_url, PHP_URL_PATH);
                    $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
                    //DELETE IMAGE FROM GCS USING SERVICE
                    app(GCSService::class)->deleteImageFromGCS($filePath);
                }
                $file = $request->file('image');
                $originalFilename = $file->getClientOriginalName();
                //UPLOAD TO GCS USING SERVICE
                $publicUrl = app(GCSService::class)->upload($file, 'uploads/cubes/' . time() . $originalFilename);
                $cube->image_url = $publicUrl;
                $cube->image_name = $originalFilename;
            }
            $cube->save();
            return redirect()->route('cube.index')->with('success', 'Cube updated successfully.!!');
        } else {
            return redirect()->route('cube.index')->with('warning', 'Something went wrong.!!');
        }
    }

    function destroy($id)
    {
        $cube = Cube::findOrFail($id);
        if (!empty($cube->image_url)) {
            $parsedUrl = parse_url($cube->image_url, PHP_URL_PATH);
            $filePath = ltrim(str_replace("/{$this->bucketName}/", '', $parsedUrl), '/');
            app(GCSService::class)->deleteImageFromGCS($filePath);
        }
        $cube->delete();
        return redirect()->route('cube.index')->with('danger', 'Cube deleted successfully.!!');
    
    }

    function toggleStatus($id)
    {
        $cube = Cube::findOrFail($id);
        $cube->status = !$cube->status;
        $cube->save();
        return redirect()->route('cube.index')->with('success', 'Cube status updated successfully.');
    }
}
