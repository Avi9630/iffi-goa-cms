<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalApiService
{
    protected $uploadUrl;
    protected $imageListUrl;

    public function __construct()
    {
        $this->uploadUrl = config('services.example_api.upload_image_base_url');
        $this->imageListUrl = config('services.example_api.image_list_base_url');
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
            ->post($this->uploadUrl, [
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

    // public function getImageList($destination)
    // {
    //     $response = Http::asMultipart()->post($this->imageListUrl, [
    //         [
    //             'name' => 'destination',
    //             'contents' => $destination,
    //         ],
    //     ]);
    //     if ($response->successful()) {
    //         return $response->json(); // Or handle as needed
    //     } else {
    //         return response()->json(
    //             [
    //                 'message' => 'Upload failed',
    //                 'status' => $response->status(),
    //                 'body' => $response->body(),
    //             ],
    //             $response->status(),
    //         );
    //     }
    // }

    public function getImageList($destination)
    {
        $response = Http::asMultipart()->post($this->imageListUrl, [
            [
                'name' => 'destination',
                'contents' => $destination,
            ],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => 'Upload failed',
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }
}
