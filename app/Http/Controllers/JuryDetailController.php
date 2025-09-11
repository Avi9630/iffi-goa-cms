<?php

namespace App\Http\Controllers;

use App\Models\IndianPanoramaOfficialSelection;
use App\Services\ExternalApiService;
use Illuminate\Validation\Rule;
use App\Services\ConvertToWEBP;
use Illuminate\Http\Request;
use App\Models\JuryDetail;

class JuryDetailController extends Controller
{
    public function __construct()
    {
        $this->mainUrl = env('IMAGE_UPLOAD_BASE_URL');
        $this->juryPath = env('JURY_IMAGE_DESTINATION');
    }

    function index(Request $request)
    {
        $payload = $request->all();
        $juryDetails = JuryDetail::with('officialSelection')->get();
        $IPOfficialSelections = IndianPanoramaOfficialSelection::all();
        return view('jury_detail.index', compact(['juryDetails', 'IPOfficialSelections', 'payload']));
    }

    public function search(Request $request)
    {
        $payload = $request->only(['official_selection_id', 'name', 'year']);
        $juryDetails = JuryDetail::query()
            ->when(
                isset($payload['official_selection_id']),
                fn($q) =>
                $q->where('official_selection_id', $payload['official_selection_id'])
            )
            ->when(
                isset($payload['name']),
                fn($q) =>
                $q->where('name', 'like', '%' . $payload['name'] . '%')
            )
            ->when(
                isset($payload['year']),
                fn($q) =>
                $q->where('year', $payload['year'])
            )
            ->get();

        $IPOfficialSelections = IndianPanoramaOfficialSelection::all(['id', 'title']);
        return view('jury_detail.index', compact('juryDetails', 'IPOfficialSelections', 'payload'));
    }

    function create()
    {
        $IPOfficialSelections = IndianPanoramaOfficialSelection::all();
        return view('jury_detail.create', compact('IPOfficialSelections'));
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'official_selection_id' => 'required',
            'name' => 'required|string|max:255',
            'year' => 'required',
            'position' => 'nullable',
            'image' => ['nullable', Rule::when($request->position === 'CHAIRPERSON', 'required_without:image_url'), 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_url' => ['nullable', Rule::when($request->position === 'CHAIRPERSON', 'required_without:image'), 'string', 'max:255'],
        ]);
        $juryDetail = new JuryDetail();
        $juryDetail->official_selection_id = $payload['official_selection_id'];
        $juryDetail->name = $payload['name'];
        $juryDetail->year = $payload['year'];
        $juryDetail->position = $payload['position'] ?? null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->juryPath);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->juryPath);
            if ($convertInWebp) {
                $juryDetail->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $juryDetail->img_url = null;
            }
        } else {
            $juryDetail->img_url = $payload['image_url'];
            $juryDetail->img_src = null;
        }

        if ($juryDetail->save()) {
            return redirect()->route('jury-detail.index')->with('success', 'Jury created successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create.! Please try again.!!');
        }
    }

    function edit($id)
    {
        $juryDetail = JuryDetail::findOrFail($id);
        $IPOfficialSelections = IndianPanoramaOfficialSelection::all();
        return view('jury_detail.edit', compact(['juryDetail', 'IPOfficialSelections']));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $request->validate([
            'official_selection_id' => 'required',
            'name' => 'required|string|max:255',
            'year' => 'required',
            'position' => 'nullable',
            'image' => ['nullable', Rule::when($request->position === 'CHAIRPERSON', 'required_without:image_url'), 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_url' => ['nullable', Rule::when($request->position === 'CHAIRPERSON', 'required_without:image'), 'string', 'max:255'],
        ]);
        $juryDetail = JuryDetail::findOrFail($id);
        $juryDetail->official_selection_id = $payload['official_selection_id']?? $juryDetail->official_selection_id;;
        $juryDetail->name = $payload['name']?? $juryDetail->name;
        $juryDetail->year = $payload['year'] ?? $juryDetail->year;
        $juryDetail->position = $payload['position'] ?? $juryDetail->position;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->juryPath);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->juryPath);
            if ($convertInWebp) {
                $juryDetail->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $juryDetail->img_url = null;
            }
        } else {
            $juryDetail->img_url = $payload['image_url'];
            $juryDetail->img_src = null;
        }
        if ($juryDetail->save()) {
            return redirect()->route('jury-detail.index')->with('success', 'Jury created successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create.! Please try again.!!');
        }
    }

    function toggle($id)
    {
        $juryDetail = JuryDetail::findOrFail($id);
        $juryDetail->status = $juryDetail->status === 1 ? 0 : 1;
        $juryDetail->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function destroy($id)
    {
        $juryDetail = JuryDetail::findOrFail($id);
        $juryDetail->delete();
        return redirect()->route('jury-detail.index')->with('danger', 'Peacock deleted successfully.!!');
    }
}
