<?php

namespace App\Http\Controllers;

use App\Models\IndianPanoramaOfficialSelection;
use App\Services\ExternalApiService;
use Illuminate\Validation\Rule;
use App\Services\ConvertToWEBP;
use App\Http\Traits\CONSTTrait;
use Illuminate\Http\Request;
use App\Models\JuryDetail;

class JuryDetailController extends Controller
{
    use CONSTTrait;
    public function __construct()
    {
        $this->mainUrl = env('IMAGE_UPLOAD_BASE_URL');
        $this->juryPath = env('JURY_IMAGE_DESTINATION');
    }

    function index(Request $request)
    {
        $payload = $request->all();
        $juryDetails = JuryDetail::orderBy('id', 'DESC')->get();
        $juryTypes = $this->juryType();
        return view('jury_detail.index', compact(['juryDetails', 'juryTypes', 'payload']));
    }

    public function search(Request $request)
    {
        $payload = $request->only(['jury_type_id', 'name', 'year']);
        $juryDetails = JuryDetail::query()
            ->when(isset($payload['jury_type_id']), fn($q) => $q->where('jury_type_id', $payload['jury_type_id']))
            ->when(isset($payload['name']), fn($q) => $q->where('name', 'like', '%' . $payload['name'] . '%'))
            ->when(isset($payload['year']), fn($q) => $q->where('year', $payload['year']))
            ->get();

        $juryTypes = $this->juryType();
        return view('jury_detail.index', compact('juryDetails', 'juryTypes', 'payload'));
    }

    function create()
    {
        $juryTypes = $this->juryType();
        return view('jury_detail.create', compact('juryTypes'));
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'jury_type_id' => 'required',
            'name' => 'required|string|max:255',
            'is_chairperson' => 'nullable',
            'designation' => 'nullable',
            'description' => 'nullable',
            'year' => 'required',
            'image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'image_url' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $juryDetail = new JuryDetail();
        $juryDetail->jury_type_id = $payload['jury_type_id'];
        $juryDetail->name = $payload['name'];
        $juryDetail->year = $payload['year'];
        $juryDetail->designation = $payload['designation'] ?? null;
        $juryDetail->is_chairperson = $payload['is_chairperson'] ?? 0;
        $juryDetail->description = $payload['description'] ?? null;

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
        $juryTypes = $this->juryType();
        return view('jury_detail.edit', compact(['juryDetail', 'juryTypes']));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $request->validate([
            'jury_type_id' => 'required',
            'name' => 'required|string|max:255',
            'is_chairperson' => 'nullable',
            'designation' => 'nullable',
            'description' => 'nullable',
            'year' => 'required',
            'image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'image_url' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $juryDetail = JuryDetail::findOrFail($id);

        $juryDetail->jury_type_id = $payload['jury_type_id'] ?? $juryDetail->jury_type_id;
        $juryDetail->name = $payload['name'] ?? $juryDetail->name;
        $juryDetail->year = $payload['year'] ?? $juryDetail->year;
        $juryDetail->designation = isset($payload['designation']) && !empty($payload['designation']) ? $payload['designation'] : '';
        $juryDetail->is_chairperson = isset($payload['is_chairperson']) && !empty($payload['is_chairperson']) ? $payload['is_chairperson'] : 0;
        $juryDetail->description = isset($payload['description']) && !empty($payload['description']) ? $payload['description'] : null;

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
