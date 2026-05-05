<?php

namespace App\Http\Controllers;

use App\Services\ExternalApiService;
use App\Models\PressRelease;
use Illuminate\Http\Request;

class PressReleaseController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->destination = env('PRESS_RELEASE');
    }

    function index(Request $request)
    {
        $pressReleases = PressRelease::where('year',2025)->orderBy('id', 'DESC')->paginate(10);
        return view('press_release.index', compact(['pressReleases']));
    }

    function toggleStatus($id)
    {
        $ticker = PressRelease::findOrFail($id);
        $ticker->status = $ticker->status === 1 ? 0 : 1;
        $ticker->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function pressSearch(Request $request)
    {
        $payload = $request->only(['pr_category_id', 'title', 'year']);
        $pressReleases = PressRelease::query()
            ->when(
                isset($payload['pr_category_id']),
                fn($q) =>
                $q->where('pr_category_id', $payload['pr_category_id'])
            )
            ->when(
                isset($payload['title']),
                fn($q) =>
                $q->where('title', 'like', '%' . $payload['title'] . '%')
            )
            ->when(
                isset($payload['year']),
                fn($q) =>
                $q->where('year', $payload['year'])
            )
            ->orderByDesc('id')
            ->paginate(10);
        $prCategory = [
            1=>'PRESS RELEASES BY PIB',
            2=>'MEDIA COVERAGE (NON PIB)',
        ];
        return view('press_release.index', compact('pressReleases', 'prCategory', 'payload'));
    }

    function create()
    {
        return view('press_release.create');
    }

    function store(Request $request)
    {
        $payload = $request->all();
        
        $request->validate(
            [
                'pr_category_id'    =>  'required|integer',
                'title'             =>  'required|string|max:255',
                'image'             =>  'nullable|mimes:pdf|required_without:link',
                'link'              =>  'nullable|required_without:image',
                'year'              =>   'required|integer',
            ],
            [
                'title.required' => 'Please enter the title.',
                'image.required_without' => 'Please upload a PDF file or provide a link.',
                'image.mimes' => 'The file must be a PDF only.',
                'image.max' => 'The PDF must not be larger than 2 MB.',
                'link.required_without' => 'Please provide a link or upload a PDF file.',
                'link.url' => 'The link format is invalid.',
            ],
        );

        $pressRelease = new PressRelease();
        $pressRelease->title            = $payload['title'];
        $pressRelease->pr_category_id   = $payload['pr_category_id'];
        $pressRelease->year             = $payload['year'];
        $pressRelease->publish_date     = date('Y-m-d');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $pressRelease->img_src = $originalFilename;
            $pressRelease->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . $originalFilename;
            $pressRelease->link = null;
        } elseif ($request->filled('link')) {
            $pressRelease->link = $request->link;
            $pressRelease->img_src = null;
            $pressRelease->image_url = null;
        }

        if($pressRelease->save()) {
            return redirect()->route('press-release.index')->with('success', 'Press Release created successfully.');
        }else{
            return redirect()->back()->with('danger', 'Something went wrong. Please try again.');
        }
    }

    function edit($id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        return view('press_release.edit', compact('pressRelease'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $pressRelease = PressRelease::findOrFail($id);
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'image' => [
                    'nullable',
                    'mimes:pdf',
                    'max:2048',
                    function ($attribute, $value, $fail) use ($request, $pressRelease) {
                        if (!$value && empty($request->link) && empty($pressRelease->image)) {
                            $fail('Please upload a PDF file or provide a link.');
                        }
                    },
                ],
                'link' => [
                    'nullable',
                    function ($attribute, $value, $fail) use ($request, $pressRelease) {
                        if (!$value && empty($request->image) && empty($press->link)) {
                            $fail('Please provide a link or upload a PDF file.');
                        }
                    },
                ],
                'pr_category_id'    =>  'required|integer',
                'year'              =>   'required|integer',
            ],
            [
                'title.required' => 'Please enter the title.',
                'image.mimes' => 'The file must be a PDF only.',
                'image.max' => 'The PDF must not be larger than 2 MB.',
                'link.url' => 'The link format is invalid.',
            ],
        );

        $pressRelease->title            =   $payload['title'] ?? $pressRelease->title;
        $pressRelease->pr_category_id   =   $payload['pr_category_id'] ?? $pressRelease->pr_category_id;
        $pressRelease->year             =   $payload['year'] ?? $pressRelease->year;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->destination);
            $pressRelease->img_src = $originalFilename;
            $pressRelease->image_url = env('IMAGE_UPLOAD_BASE_URL') . $this->destination . '/' . $originalFilename;
            $pressRelease->link = null;
        } elseif ($request->filled('link')) {
            $pressRelease->link = $request->link;
            $pressRelease->img_src = null;
            $pressRelease->image_url = null;
        }
        if($pressRelease->save()){
            return redirect()->route('press-release.index')->with('success', 'Press Release updated successfully.');
        }else{
            return redirect()->back()->with('danger', 'Something went wrong. Please try again.');
        }
    }

    function destroy($id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        $pressRelease->delete();
        return redirect()->route('press-release.index')->with('danger', 'Press Release deleted successfully.!!');
    }
}
