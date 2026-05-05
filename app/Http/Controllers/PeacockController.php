<?php

namespace App\Http\Controllers;

use App\Models\Peacock;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use App\Services\GCSService;
use Illuminate\Http\Request;

class PeacockController extends Controller
{
    protected $bucketName;

    public function __construct()
    {
        $this->mainUrl = env('IMAGE_UPLOAD_BASE_URL');
        $this->posterDestination = env('PEACOCK_POSTER_DESTINATION');
        $this->PDFDestination = env('PEACOCK_PDF_DESTINATION');
    }

    function index()
    {
        $peacocks = Peacock::where('year', 2025)->orderBy('id', 'DESC')->paginate(10);
        return view('peacock.index', compact('peacocks'));
    }

    function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $peacocks = Peacock::where('title', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('year', 'LIKE', '%' . $searchTerm . '%')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $year = $request->input('search');
        return view('peacock.index', compact(['peacocks', 'year']));
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

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'title'         =>  'required|string|max:255',
            'poster'        =>  'required_without:poster_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'poster_url'    =>  'required_without:poster|nullable|string|max:255',
            'pdf'           =>  'required_without:pdf_url|mimes:pdf',
            'pdf_url'       =>  'required_without:pdf|nullable|string|max:255',
            'year'          =>  'required|integer',
        ]);

        $peacock = new Peacock();
        $peacock->title = $payload['title'] ?? null;
        $peacock->year = $payload['year'] ?? null;

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            $file       =   $request->file('poster');
            $extension  =   strtolower($file->getClientOriginalExtension());
            $upload     =   app(ExternalApiService::class)->postData($file, $this->posterDestination);
            if (!$upload['status']) {
                return redirect()->back()->with('error', 'Failed to upload image to external service. Please try again.!!');
            }
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('poster'), $this->posterDestination);
            if ($convertInWebp) {
                $peacock->poster        =   $extension === 'webp' ? $upload['data']['fileName'] : $convertInWebp;
                $peacock->poster_url    =   null;
            }
        } else {
            if ($request->filled('image_url') && !filter_var($request->image_url, FILTER_VALIDATE_URL)) {
                $peacock->poster_url = $payload['poster_url'];
                $peacock->poster = null;
            }
        }

        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            $file = $request->file('pdf');
            $originalFilename   =   $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->PDFDestination);
            $peacock->img_src       =   $originalFilename;
            $peacock->image_name    =   $originalFilename;
            $peacock->image_url     =   null;
        } else {
            $peacock->image_url = $payload['pdf_url'];
            $peacock->img_src = null;
            $peacock->image_name = null;
        }

        if ($peacock->save()) {
            return redirect()->route('peacock.index')->with('success', 'Cube uploaded successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create. Please try again.!!');
        }
    }

    function edit($id)
    {
        $peacock = Peacock::findOrFail($id);
        return view('peacock.edit', compact('peacock'));
    }

    function update(Request $request, $id, GCSService $gcsService)
    {
        $payload = $request->all();
        $peacock = Peacock::findOrFail($id);
        $rules = [
            'title'         =>  'required|string|max:255',
            'poster'        =>  'required_without:poster_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'poster_url'    =>  'nullable|string|max:255',
            'pdf_url'       =>  'nullable|string|max:255',
            'year'          =>  'required|integer|min:1800|max:' . date('Y'),
        ];

        if (empty($payload['poster_url'])) {
            if ($peacock->poster == null && !$request->hasFile('poster')) {
                $rules['poster'] = 'required|file|mimes:jpg,jpeg,png,webp';
            } else {
                $rules['poster'] = 'nullable|file|mimes:jpg,jpeg,png,webp';
            }
        }

        if (empty($payload['pdf_url'])) {
            if ($peacock->img_src == null && !$request->hasFile('pdf')) {
                $rules['pdf'] = 'required|file|mimes:pdf';
            } else {
                $rules['pdf'] = 'nullable|file|mimes:pdf';
            }
        }

        $validated = $request->validate($rules);

        if ($peacock) {

            $peacock->title = $validated['title'] ?? null;
            $peacock->year = $validated['year'] ?? null;

            if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
                $file               =   $request->file('poster');
                $originalFilename   =   $file->getClientOriginalName();
                $extension          =   strtolower($file->getClientOriginalExtension());
                $upload             =   app(ExternalApiService::class)->postData($file, $this->posterDestination);

                if (!$upload['status']) {
                    return redirect()->back()->with('error', 'Failed to upload image to external service. Please try again.!!');
                }
                $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('poster'), $this->posterDestination);
                if ($convertInWebp) {
                    $peacock->poster = $extension === 'webp' ? $upload['data']['fileName'] : $convertInWebp;
                    $peacock->poster_url = null;
                }
            } else {
                if (isset($payload['poster_url']) && !empty($payload['poster_url'])) {
                    $peacock->poster_url = $payload['poster_url'];
                    $peacock->poster = null;
                }
            }

            if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
                $file = $request->file('pdf');
                $originalFilename = $file->getClientOriginalName();
                app(ExternalApiService::class)->postData($file, $this->PDFDestination);
                $peacock->img_src = $originalFilename;
                $peacock->image_name = $originalFilename;
                $peacock->image_url = null;
            } else {
                if (isset($payload['pdf_url']) && !empty($payload['pdf_url'])) {
                    $peacock->image_url = $payload['pdf_url'];
                    $peacock->img_src = null;
                    $peacock->image_name = null;
                }
            }
            $peacock->save();
            return redirect()->route('peacock.index')->with('success', 'Peacock entry updated successfully.');
            
        } else {
            return redirect()->back()->with('warning', 'Something went wrong with records.!!');
        }
        // $peacock->save();
        // return redirect()->route('peacock.index')->with('success', 'Press Release updated successfully.');
    }

    function destroy($id)
    {
        $peacock = Peacock::findOrFail($id);
        $peacock->delete();
        return redirect()->route('peacock.index')->with('danger', 'Peacock deleted successfully.!!');
    }
}
