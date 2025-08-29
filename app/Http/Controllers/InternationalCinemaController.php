<?php

namespace App\Http\Controllers;

use App\Models\CuratedSection;
use App\Models\InternationalCinema;
use App\Models\InternationalCinemaBasicDetail;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;

class InternationalCinemaController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->bucketName = config('services.gcs.bucket');
        $this->destination = env('INTERNATIONAL_CINEMA');
    }

    public function index()
    {
        $internationalCinemas = InternationalCinema::orderBy('id', 'DESC')->paginate(10);
        $curatedSections = CuratedSection::all();
        return view('international_cinema.index', compact(['internationalCinemas', 'curatedSections']));
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
        $validated = $request->validate([
            'curated_section_id' => 'required|exists:curated_sections,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'directed_by' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
                $internationalCinema->img_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
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
        $validated = $request->validate([
            'curated_section_id' => 'required|exists:curated_sections,id',
            'title' => 'required|string|max:300',
            'slug' => 'required|string|max:255',
            'directed_by' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
                    $internationalCinema->img_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                }
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

    // function storeBasicDetail(Request $request)
    // {
    //     $payload = $request->all();
    //     $internationalCinema = InternationalCinema::findOrFail($payload['cinema_id']);
    //     if ($internationalCinema) {
    //         $internationalCinemaBasicDetail = new InternationalCinemaBasicDetail();
    //         $internationalCinemaBasicDetail['cinema_id'] = $payload['cinema_id'];
    //         $internationalCinemaBasicDetail['director'] = $payload['director'] ?? null;
    //         $internationalCinemaBasicDetail['producer'] = $payload['producer'] ?? null;
    //         $internationalCinemaBasicDetail['screenplay'] = $payload['screenplay'] ?? null;
    //         $internationalCinemaBasicDetail['cinematographer'] = $payload['cinematographer'] ?? null;
    //         $internationalCinemaBasicDetail['editor'] = $payload['editor'] ?? null;
    //         $internationalCinemaBasicDetail['cast'] = $payload['cast'] ?? null;
    //         $internationalCinemaBasicDetail['dop'] = $payload['dop'] ?? null;
    //         $internationalCinemaBasicDetail['other_details'] = $payload['other_details'] ?? null;
    //         $internationalCinemaBasicDetail['synopsis'] = $payload['synopsis'] ?? null;
    //         $internationalCinemaBasicDetail['director_bio'] = $payload['director_bio'] ?? null;
    //         $internationalCinemaBasicDetail['producer_bio'] = $payload['producer_bio'] ?? null;
    //         $internationalCinemaBasicDetail['sales_agent'] = $payload['sales_agent'] ?? null;
    //         $internationalCinemaBasicDetail['award'] = $payload['award'] ?? null;
    //         $internationalCinemaBasicDetail['writer'] = $payload['writer'] ?? null;
    //         $internationalCinemaBasicDetail['trailer_link'] = $payload['trailer_link'] ?? null;
    //         $internationalCinemaBasicDetail['official_selection'] = $payload['official_selection'] ?? null;
    //         $internationalCinemaBasicDetail['best_film_award'] = $payload['best_film_award'] ?? null;
    //         $internationalCinemaBasicDetail['director_and_producer'] = $payload['director_and_producer'] ?? null;
    //         $internationalCinemaBasicDetail['original_title'] = $payload['original_title'] ?? null;
    //         $internationalCinemaBasicDetail['co_produced'] = $payload['co_produced'] ?? null;
    //         $internationalCinemaBasicDetail['festivals'] = $payload['festivals'] ?? null;
    //         $internationalCinemaBasicDetail['drama'] = $payload['drama'] ?? null;
    //         $internationalCinemaBasicDetail['history'] = $payload['history'] ?? null;
    //         $internationalCinemaBasicDetail['nomination'] = $payload['nomination'] ?? null;
    //         $internationalCinemaBasicDetail['status'] = 1;
    //         $internationalCinemaBasicDetail->save();
    //         return redirect()->route('international-cinema.index')->with('success', 'Basic details addedd successfully.!!');
    //     } else {
    //         return redirect()->route('international-cinema.index')->with('warning', 'Something went wrong.!!');
    //     }
    // }
}
