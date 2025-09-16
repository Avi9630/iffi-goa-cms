<?php

namespace App\Http\Controllers;

use App\Models\InternationalCinemaBasicDetail;
use App\Services\ExternalApiService;
use App\Models\InternationalCinema;
use App\Services\ConvertToWEBP;
use App\Models\CuratedSection;
use App\Services\GCSService;
use Illuminate\Http\Request;

class InternationalCinemaController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->destination = env('INTERNATIONAL_CINEMA');
    }

    public function index(Request $request)
    {
        $payload = $request->all();
        $internationalCinemas = InternationalCinema::orderBy('id', 'DESC')
            ->where(['award_year' => 2025, 'year' => 2025])
            ->paginate(10);
        $curatedSections = CuratedSection::all();
        return view('international_cinema.index', compact(['internationalCinemas', 'curatedSections', 'payload']));
    }

    function search(Request $request)
    {
        $payload = $request->all();
        $searchTerm = $request->input('search');
        $internationalCinemas = InternationalCinema::where('award_year', $searchTerm)->orderBy('id', 'DESC')->paginate(10);
        $curatedSections = CuratedSection::all();
        return view('international_cinema.index', compact(['internationalCinemas', 'curatedSections']));
    }

    public function fullSearch(Request $request)
    {
        $payload = $request->only(['curated_section_id', 'title', 'year']);
        $internationalCinemas = InternationalCinema::query()
            ->when(isset($payload['curated_section_id']), fn($q) => $q->where('curated_section_id', $payload['curated_section_id']))
            ->when(isset($payload['title']), fn($q) => $q->where('title', 'like', '%' . $payload['title'] . '%'))
            ->when(isset($payload['year']), fn($q) => $q->where('award_year', $payload['year']))
            ->orderByDesc('id')
            ->paginate(10);
        $curatedSections = CuratedSection::all();
        return view('international_cinema.index', compact('internationalCinemas', 'curatedSections', 'payload'));
    }

    function toggleStatus($id)
    {
        $internationalCinema = InternationalCinema::findOrFail($id);
        $internationalCinema->status = $internationalCinema->status === 1 ? 0 : 1;
        $internationalCinema->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function create()
    {
        $curatedSections = CuratedSection::all();
        return view('international_cinema.create', compact('curatedSections'));
    }

    public function store(Request $request)
    {
        $payload = $request->all();
        $validated = $request->validate([
            'curated_section_id' => 'required|exists:curated_sections,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'directed_by' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            // 'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image' => 'required_without:image_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'required_without:image|nullable|string|max:255',
            'year' => 'required|integer|min:1800|max:' . date('Y'),
            'award_year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        $internationalCinema = new InternationalCinema();
        $internationalCinema->curated_section_id = $validated['curated_section_id'];
        $internationalCinema->title = $validated['title'];
        $internationalCinema->slug = $validated['slug'];
        $internationalCinema->directed_by = $validated['directed_by'];
        $internationalCinema->country_of_origin = $validated['country_of_origin'];
        $internationalCinema->language = $validated['language'];
        $internationalCinema->year = $validated['year'];
        $internationalCinema->award_year = $validated['award_year'];
        $internationalCinema->status = 1;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
            if ($convertInWebp) {
                $internationalCinema->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                // $internationalCinema->img_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $internationalCinema->img_url = null;
            }
        } else {
            if ($request->filled('image_url') && !filter_var($request->image_url, FILTER_VALIDATE_URL)) {
                $internationalCinema->img_url = $payload['image_url'];
                $internationalCinema->img_src = null;
            }
        }
        $internationalCinema->save();
        return redirect()->route('international-cinema.index')->with('success', 'International Cinema created successfully.');
    }

    function edit($id)
    {
        $internationalCinema = InternationalCinema::findOrFail($id);
        $curatedSections = CuratedSection::all();
        return view('international_cinema.edit', compact(['internationalCinema', 'curatedSections']));
    }

    function update(Request $request, GCSService $gcsService, $id)
    {
        $payload = $request->all();
        $validated = $request->validate([
            'curated_section_id' => 'required|exists:curated_sections,id',
            'title' => 'required|string|max:300',
            'slug' => 'required|string|max:255',
            'directed_by' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image' => 'required_without:image_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url' => 'required_without:image|nullable|string|max:255',
            'year' => 'required|integer|min:1800|max:' . date('Y'),
            'award_year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        $internationalCinema = InternationalCinema::findOrFail($id);

        if ($internationalCinema) {
            $internationalCinema->curated_section_id = $validated['curated_section_id'] ?? null;
            $internationalCinema->title = $validated['title'];
            $internationalCinema->slug = $validated['slug'];
            $internationalCinema->directed_by = $validated['directed_by'];
            $internationalCinema->country_of_origin = $validated['country_of_origin'];
            $internationalCinema->language = $validated['language'];
            $internationalCinema->year = $validated['year'];
            $internationalCinema->award_year = $validated['award_year'];

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if ($internationalCinema->img_url) {
                    $gcsService->deleteImageFromGCS($internationalCinema->img_url);
                }
                $file = $request->file('image');
                $originalFilename = $file->getClientOriginalName();
                app(ExternalApiService::class)->postData($file, $this->destination);
                $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->destination);
                if ($convertInWebp) {
                    $internationalCinema->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                    // $internationalCinema->img_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                    $internationalCinema->img_url = null;
                }
            } else {
                $internationalCinema->img_url = $payload['image_url'];
                $internationalCinema->img_src = null;
            }

            $internationalCinema->save();
            return redirect()->route('international-cinema.index')->with('success', 'International Cinema entry updated successfully.');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong with records.!!');
        }
    }

    function destroy($id)
    {
        $internationalCinema = InternationalCinema::findOrFail($id);
        $internationalCinema->delete();
        return redirect()->route('international-cinema.index')->with('danger', 'Entry deleted successfully.!!');
    }

    function addBasicDetail($id)
    {
        $internationalCinema = InternationalCinema::find($id);
        return view('international_cinema.basic-detail', compact('internationalCinema'));
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
                $row = array_map(function ($field) {
                    return $field !== null ? mb_convert_encoding($field, 'UTF-8', 'UTF-8, ISO-8859-1, CP1252') : null;
                }, $row);

                if (!$header) {
                    $header = $row;
                    continue;
                }

                $data = [
                    'section' => $row[0] ?? null,
                    'title' => $row[1] ?? null,
                    'original_title' => $row[2] ?? null,
                    'country' => $row[3] ?? null,
                    'production_year' => $row[4] ?? null,
                    'language' => $row[5] ?? null,
                    'runtime' => $row[6] ?? null,
                    'color' => $row[7] ?? null,
                    'director' => $row[8] ?? null,
                    'director_bio' => $row[9] ?? null,
                    'producer' => $row[10] ?? null,
                    'screenplay' => $row[11] ?? null,
                    'dop' => $row[12] ?? null,
                    'editor' => $row[13] ?? null,
                    'cast' => $row[14] ?? null,
                    'synopsis' => $row[15] ?? null,
                    'premiere' => $row[16] ?? null,
                    'award' => $row[17] ?? null,
                    'festival_history' => $row[18] ?? null,
                    'trailer_link' => $row[19] ?? null,
                    'tags' => $row[20] ?? null,
                    'sales' => $row[21] ?? null,
                    'instagram' => $row[26] ?? null,
                    'twitter' => $row[27] ?? null,
                    'facebook' => $row[28] ?? null,
                    'award_year' => $row[29] ?? null,
                    'co_screenplay' => $row[30] ?? null,
                    'cinematographer' => $row[31] ?? null,
                    'producer_bio' => $row[32] ?? null,
                ];
                $curated = CuratedSection::where('title', $data['section'])->first();
                if (!$curated) {
                    continue;
                }
                $cinema = InternationalCinema::updateOrCreate(
                    [
                        'title' => $data['title'],
                        'award_year' => $data['award_year'],
                    ],
                    [
                        'curated_section_id' => $curated->id,
                        'slug' => str_replace(' ', '-', $data['title']),
                        'directed_by' => $data['director'],
                        'country_of_origin' => $data['country'],
                        'language' => $data['language'],
                        'year' => $data['production_year'],
                        'status' => 1,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ],
                );

                InternationalCinemaBasicDetail::updateOrCreate(
                    [
                        'cinema_id' => $cinema->id,
                    ],
                    [
                        'director' => $data['director'],
                        'producer' => $data['producer'],
                        'screenplay' => $data['screenplay'],
                        'co_screenplay' => $data['co_screenplay'],
                        'cinematographer' => $data['cinematographer'],
                        'editor' => $data['editor'],
                        'cast' => $data['cast'],
                        'dop' => $data['dop'],
                        'other_details' => "{$data['runtime']} | {$data['color']} | {$data['country']}",
                        'synopsis' => $data['synopsis'],
                        'director_bio' => $data['director_bio'],
                        'producer_bio' => $data['producer_bio'],
                        'sales_agent' => $data['sales'],
                        'award' => $data['award'],
                        'trailer_link' => $data['trailer_link'],
                        'original_title' => $data['original_title'],
                        'premiere' => $data['premiere'],
                        'festival_history' => $data['festival_history'],
                        'link_trailer' => $data['trailer_link'],
                        'tags' => $data['tags'],
                        'instagram' => $data['instagram'],
                        'twitter' => $data['twitter'],
                        'facebook' => $data['facebook'],
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
