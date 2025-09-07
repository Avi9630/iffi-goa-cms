<?php

namespace App\Http\Controllers;

use App\Models\Cube;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;

class CubeController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->destination = env('CUBE_DESTINATION');
    }

    function index()
    {
        $cubes = Cube::orderBy('id','DESC')->get();
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
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'required|string',
        ]);

        $cube = new Cube();
        $cube->link = $payload['link'];
        
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);            
            if ($convertInWebp) {
                $cube->image_name = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $cube->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
            }
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'required|string',
        ]);

        $cube = Cube::findOrFail($id);

        if ($cube) {
            $cube->link = $payload['link'] ?? null;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $originalFilename = $file->getClientOriginalName();
                app(ExternalApiService::class)->postData($file, $this->destination);
                $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
                if ($convertInWebp) {
                    $cube->image_name = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                    $cube->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                }
                // $cube->image_name = $originalFilename;
                // $cube->image_url = 'https://www.iffigoa.org/public/images/cube/webp/' . $originalFilename;
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
