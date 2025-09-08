<?php

namespace App\Http\Controllers;

use App\Services\ExternalApiService;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    function getImageByFolder(Request $request, $path)
    {
        $response = app(ExternalApiService::class)->getImageList($path);
        if (!empty($response['error'])) {
            return back()->withErrors(['msg' => $response['message']]);
        }
        $images = $response['files'] ?? [];
        return view('news_update.image', compact('images'));
    }

    public function downloadSampleCsv($fileName)
    {
        return response()->download(storage_path('app/CSV/' . $fileName));
    }
}
