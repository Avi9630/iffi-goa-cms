<?php

namespace App\Services;


use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;

class ConvertToWEBP
{

    // public function convert($file, string $destinationPath): string
    // {
    //     try {
    //         $manager = new ImageManager(new Driver());
    //         $files = glob($folderPath . '*.{jpg,jpeg,png,avif,jfif,webp,PNG}', GLOB_BRACE);
    //         if (empty($files)) {
    //             $this->info("No image files found in: {$folderPath}");
    //             return 0;
    //         }
    //         foreach ($files as $filePath) {
    //             $this->info('Processing: ' . basename($filePath));
    //             $img = $manager->read($filePath);
    //             // Create the new webp directory
    //             $outputDir = $folderPath . 'webp/';
    //             if (!file_exists($outputDir)) {
    //                 mkdir($outputDir, 0755, true);
    //             }
    //             $filenameWithoutExt = pathinfo($filePath, PATHINFO_FILENAME);
    //             $webpPath = $outputDir . $filenameWithoutExt . '.webp';
    //             // dd($webpPath);
    //             $img->toWebp(75)->save($webpPath);
    //             $this->info("Converted to: {$webpPath}");
    //         }
    //     } catch (\Exception $e) {
    //         $this->error('Conversion failed: ' . $e->getMessage());
    //     }

    //     return 0;
    // }

    // public function convert($file, string $destinationPath): string
    // {
    //     dd($file->getClientOriginalName());
    //     $storage_url = env('STORAGE_URL', '/var/www/html/api/');
    //     try {
    //         $manager = new ImageManager(new Driver());
    //         $img = $manager->read($file);
    //         // Create the new webp directory if it doesn't exist
    //         if (!file_exists($destinationPath)) {
    //             mkdir($destinationPath, 0755, true);
    //         }
    //         $filenameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
    //         $webpPath = $destinationPath . '/' . $filenameWithoutExt . '.webp';
    //         $img->toWebp(75)->save($webpPath);
    //         return $webpPath;
    //     } catch (\Exception $e) {
    //         throw new \Exception('Conversion failed: ' . $e->getMessage());
    //     }
    // }

    public function convert($file, string $destinationPath): string
    {
        // dd($destinationPath);
        try {
            // $filePath = '/var/www/html/iffi-goa/public/'.$destinationPath.'/'.$file->getClientOriginalName();
            // $manager = new ImageManager(new Driver());
            // if (!file_exists($filePath)) {
            //     throw new \Exception("File does not exist: {$filePath}");
            // }
            // $img = $manager->read($filePath);
            // $img->toWebp(75)->save($filePath);
            // return $filePath;
            $filePath = '/var/www/html/iffi-goa/public/' . $destinationPath . '/' . $file->getClientOriginalName();

            $manager = new ImageManager(new Driver());

            if (!file_exists($filePath)) {
                throw new \Exception("File does not exist: {$filePath}");
            }

            // Read the original image
            $img = $manager->read($filePath);

            // Create new path with .webp extension
            $filenameWithoutExt = pathinfo($filePath, PATHINFO_FILENAME);
            $webpPath = dirname($filePath) . '/' . $filenameWithoutExt . '.webp';

            // Save as WebP
            $img->toWebp(40)->save($webpPath);

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Conversion failed: ' . $e->getMessage());
        }
    }
}
