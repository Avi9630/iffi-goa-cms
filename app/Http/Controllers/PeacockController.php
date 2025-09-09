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
        $peacocks = Peacock::orderBy('id', 'DESC')->paginate(10);
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
            $file = $request->file('poster');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->posterDestination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('poster'), $this->posterDestination);
            if ($convertInWebp) {
                $peacock->poster = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                // $peacock->poster_url = env('IMAGE_UPLOAD_BASE_URL') . $this->posterDestination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $peacock->poster_url = null;
            }
        }else {
            $peacock->poster_url = $payload['poster_url'];
            $peacock->poster = null;
        }

        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            $file = $request->file('pdf');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->PDFDestination);
            $peacock->img_src = $originalFilename;
            $peacock->image_name = $originalFilename;
            // $peacock->image_url = $this->mainUrl . $this->PDFDestination . '/' . $originalFilename;
            $peacock->image_url = null;
        }else {
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
        $request->validate([
            'title'         =>  'required|string|max:255',
            'poster'        =>  'required_without:poster_url|file|mimes:jpg,jpeg,png,webp|max:2048',
            'poster_url'    =>  'required_without:poster|nullable|string|max:255',
            'pdf'           =>  'required_without:pdf_url|mimes:pdf',
            'pdf_url'       =>  'required_without:pdf|nullable|string|max:255',
            'year'          =>  'nullable|integer',
        ]);

        $peacock = Peacock::findOrFail($id);
        $peacock->title = $request->title ?? $peacock->title;
        $peacock->year = $request->year ?? $peacock->year;

        if ($request->hasFile('poster') && $request->file('poster')->isValid()) {
            $file = $request->file('poster');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->posterDestination);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('poster'), $this->posterDestination);
            if ($convertInWebp) {
                $peacock->poster = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                // $peacock->poster_url = env('IMAGE_UPLOAD_BASE_URL') . $this->posterDestination . '/' . pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $peacock->poster_url = null;
            }
        }else {
            $peacock->poster_url = $payload['poster_url'];
            $peacock->poster = null;
        }

        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            $file = $request->file('pdf');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->PDFDestination);
            $peacock->img_src = $originalFilename;
            $peacock->image_name = $originalFilename;
            // $peacock->image_url = $this->mainUrl . $this->PDFDestination . '/' . $originalFilename;
            $peacock->image_url = null;
        }else {
            $peacock->image_url = $payload['pdf_url'];
            $peacock->img_src = null;
            $peacock->image_name = null;
        }

        $peacock->save();
        return redirect()->route('peacock.index')->with('success', 'Press Release updated successfully.');
    }

    function destroy($id)
    {
        $peacock = Peacock::findOrFail($id);
        $peacock->delete();
        return redirect()->route('peacock.index')->with('danger', 'Peacock deleted successfully.!!');
    }
}
