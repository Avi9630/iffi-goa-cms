<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.example_api.base_url');
    }

    public function getPosts($file, $destination)
    {
        dd($file);
        $response = Http::get($this->baseUrl . '/posts');

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body(),
        ];
    }

    public function postData($file, $destination)
    {
        // $response = Http::asMultipart()->post($this->baseUrl, [
        //     [
        //         'name' => 'image',
        //         'contents' => fopen($file->getPathname(), 'r'),
        //         'filename' => $file->getClientOriginalName(),
        //     ],
        //     [
        //         'name' => 'destination',
        //         'contents' => $destination,
        //     ],
        // ]);
        $response = Http::withOptions(['verify' => false])
            ->asMultipart()
            ->post($this->baseUrl, [
                [
                    'name' => 'image',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ],
                [
                    'name' => 'destination',
                    'contents' => $destination,
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(
                [
                    'message' => 'Upload failed',
                    'status' => $response->status(),
                    'body' => $response->body(),
                ],
                $response->status(),
            );
        }
    }

    public function getImageList($destination)
    {
        $response = Http::asMultipart()->post('http://localhost/iffi-goa/api/list-image-from-folder', [
            [
                'name' => 'destination',
                'contents' => $destination,
            ],
        ]);
        if ($response->successful()) {
            return $response->json(); // Or handle as needed
        } else {
            return response()->json(
                [
                    'message' => 'Upload failed',
                    'status' => $response->status(),
                    'body' => $response->body(),
                ],
                $response->status(),
            );
        }
    }
}
