<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;

class GCSService
{
    protected $projectId;
    protected $keyFilePath;
    protected $bucketName;
    protected $publicUrlFormat;

    public function __construct()
    {
        $this->projectId = config('services.gcs.project_id');
        $this->keyFilePath = config('services.gcs.key_file');
        $this->bucketName = config('services.gcs.bucket');
        $this->publicUrlFormat = config('services.gcs.public_url_format');
    }

    public function upload($file, string $destinationPath): string
    {
        $guzzleClient = new Client([
            'verify' => false,
            'curl' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_CAINFO => 'C:\php\extras\ssl\cacert.pem',
            ],
        ]);

        $storage = new StorageClient([
            'projectId' => $this->projectId,
            'keyFilePath' => $this->keyFilePath,
            'httpClient' => $guzzleClient,
        ]);

        $bucket = $storage->bucket($this->bucketName);

        $bucket->upload(fopen($file->getRealPath(), 'r'), ['name' => $destinationPath]);

        return sprintf($this->publicUrlFormat, $this->bucketName, $destinationPath);

        $bucket->upload(fopen($tempPath, 'r'), ['name' => 'newsUpdate/' . $originalFilename]);
        $publicUrl = sprintf($this->gcsApi, $this->bucketName, $originalFilename);
    }
}
