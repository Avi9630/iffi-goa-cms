<?php

namespace App\Http\Controllers;

use App\Models\IndianPanorama;
use App\Models\IndianPanoramaOfficialSelection;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;

class IndianPanoramaController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->destination = env('INDIAN_PANORAMA');
    }

    public function index(Request $request)
    {
        $payload = $request->all();
        $indianPanoramas = IndianPanorama::orderBy('id', 'DESC')->paginate(10);
        $IPOfficialSelection = IndianPanoramaOfficialSelection::all();
        return view('indian_panorama.index', compact(['indianPanoramas', 'IPOfficialSelection', 'payload']));
    }

    public function search(Request $request)
    {
        $payload = $request->only(['official_selection_id', 'year']);
        $indianPanoramas = IndianPanorama::query()
            ->when(isset($payload['official_selection_id']), fn($q) => $q->where('official_selection_id', $payload['official_selection_id']))
            ->when(isset($payload['year']), fn($q) => $q->where('year', $payload['year']))
            ->orderByDesc('id')
            ->paginate(10);
        $IPOfficialSelection = IndianPanoramaOfficialSelection::all(['id', 'title']);
        return view('indian_panorama.index', compact('indianPanoramas', 'IPOfficialSelection', 'payload'));
    }

    function toggleStatus($id)
    {
        $indianPanorama = IndianPanorama::findOrFail($id);
        $indianPanorama->status = $indianPanorama->status === 1 ? 0 : 1;
        $indianPanorama->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function create()
    {
        $IPOfficialSelections = IndianPanoramaOfficialSelection::all();
        return view('indian_panorama.create', compact('IPOfficialSelections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'official_selection_id' => 'required|exists:curated_sections,id',
            'title' => 'required|string|max:255',
            'directed_by' => 'required|string|max:255',
            'country_of_origin' => 'nullable|string|max:255',
            'language' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        $indianPanorama = new IndianPanorama();
        $indianPanorama->official_selection_id = $validated['official_selection_id'];
        $indianPanorama->title = $validated['title'];
        $indianPanorama->slug = str_replace(' ', '-', strtolower($validated['title']));
        $indianPanorama->directed_by = $validated['directed_by'];
        $indianPanorama->country_of_origin = $validated['country_of_origin'];
        $indianPanorama->language = $validated['language'];
        $indianPanorama->year = $validated['year'];
        $indianPanorama->created_by = 1;
        $indianPanorama->status = 1;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
            if ($convertInWebp) {
                $indianPanorama->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
            }
        }
        $indianPanorama->save();
        return redirect()->route('indian-panorama.index')->with('success', 'Indian Panorama created successfully.!!');
    }

    function edit($id)
    {
        $indianPanorama = IndianPanorama::findOrFail($id);
        $IPOfficialSelections = IndianPanoramaOfficialSelection::all();
        return view('indian_panorama.edit', compact(['indianPanorama', 'IPOfficialSelections']));
    }

    function update(Request $request, $id)
    {
        $validated = $request->validate([
            'official_selection_id' => 'required|exists:curated_sections,id',
            'title' => 'required|string|max:255',
            'directed_by' => 'required|string|max:255',
            'country_of_origin' => 'nullable|string|max:255',
            'language' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        $indianPanorama = IndianPanorama::findOrFail($id);

        if ($indianPanorama) {
            $indianPanorama->official_selection_id = $validated['official_selection_id'] ?? $indianPanorama->official_selection_id;
            $indianPanorama->title = $validated['title'] ?? $indianPanorama->title;
            $indianPanorama->slug = str_replace(' ', '-', strtolower($indianPanorama->title));
            $indianPanorama->directed_by = $validated['directed_by'] ?? $indianPanorama->directed_by;
            $indianPanorama->country_of_origin = $validated['country_of_origin'] ?? $indianPanorama->country_of_origin;
            $indianPanorama->language = $validated['language'] ?? $indianPanorama->language;
            $indianPanorama->year = $validated['year'] ?? $indianPanorama->year;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $originalFilename = $file->getClientOriginalName();
                app(ExternalApiService::class)->postData($file, $this->destination);
                $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
                if ($convertInWebp) {
                    $indianPanorama->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                }
            }
            $indianPanorama->save();
            return redirect()->route('indian-panorama.index')->with('success', 'Indian Panorama entry updated successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong with records.!!');
        }
    }

    function destroy($id)
    {
        $indianPanorama = IndianPanorama::findOrFail($id);
        $indianPanorama->delete();
        return redirect()->route('indian-panorama.index')->with('danger', 'Entry deleted successfully.!!');
    }

    public function uploadCSV(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'file' => 'required',
        ]);

        if (!$request->hasFile('file') && !$request->file('file')->isValid()) {
            return redirect()->back()->with('warning', 'Upload valid CSV.');
        }
        // $csvFile = storage_path('app/CSV/test1.csv');
        $csvFile = $payload['file'];

        if (!file_exists($csvFile)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        if (($handle = fopen($csvFile, 'r')) === false) {
            return response()->json(['error' => 'Could not open file.'], 500);
        }
        $header = null;
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }
                $data = [
                    'official_selection_id' => $row[0] ?? null,
                    'title' => $row[1] ?? null,
                    'directed_by' => $row[2] ?? null,
                    'country_of_origin' => $row[3] ?? null,
                    'language' => $row[4] ?? null,
                    'year' => $row[5] ?? null,
                    'created_by' => $row[6] ?? null,
                ];

                IndianPanorama::updateOrCreate(
                    [
                        'title' => $data['title'],
                        'year' => $data['year'],
                    ],
                    [
                        'official_selection_id' =>  $data['official_selection_id'],
                        'slug' => str_replace(' ', '-', strtolower($data['title'])),
                        'directed_by' => $data['directed_by'],
                        'country_of_origin' => $data['country_of_origin'],
                        'language' => $data['language'],
                        'year' => $data['year'],
                        'status' => 1,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ],
                );
            }
            fclose($handle);
            return redirect()
                ->back()
                ->with(['success' => 'CSV Imported Successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
