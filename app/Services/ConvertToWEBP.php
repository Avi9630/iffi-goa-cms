<?php

namespace App\Services;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

    // public function convert($file, string $destinationPath): string
    // {
    //     try {
    //         $extension = strtolower($file->getClientOriginalExtension());
    //         if ($extension === 'webp') {
    //             return true;
    //         }
    //         if ($_SERVER['HTTP_HOST'] === 'localhost') {
    //             $filePath = 'C:/xampp/htdocs/iffi-goa/public/' . $destinationPath . '/' . $file->getClientOriginalName();
    //         } else {
    //             $filePath = '/var/www/html/iffi-goa/public/' . $destinationPath . '/' . $file->getClientOriginalName();
    //         }
    //         $manager = new ImageManager(new Driver());
    //         if (!file_exists($filePath)) {
    //             throw new \Exception("File does not exist: {$filePath}");
    //         }
    //         $img = $manager->read($filePath);
    //         $filenameWithoutExt = pathinfo($filePath, PATHINFO_FILENAME);
    //         $webpPath = dirname($filePath) . '/' . $filenameWithoutExt . '.webp';
    //         $img->toWebp(40)->save($webpPath);
    //         if (file_exists($webpPath) && file_exists($filePath)) {
    //             unlink($filePath);
    //         }
    //         return true;
    //     } catch (\Exception $e) {
    //         throw new \Exception('Conversion failed: ' . $e->getMessage());
    //     }
    // }

    public function convert($file, string $destinationPath): string
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension());
            if ($_SERVER['HTTP_HOST'] === 'localhost') {
                $filePath = 'C:/xampp/htdocs/iffi-goa/public/' . $destinationPath . '/' . $file->getClientOriginalName();
            } else {
                $filePath = '/var/www/html/iffi-goa/public/' . $destinationPath . '/' . $file->getClientOriginalName();
            }
            $manager = new ImageManager(new Driver());
            if (!file_exists($filePath)) {
                throw new \Exception("File does not exist: {$filePath}");
            }
            $img = $manager->read($filePath);
            $filenameWithoutExt = pathinfo($filePath, PATHINFO_FILENAME);
            $dir = dirname($filePath);
            $webpPath = $dir . '/' . $filenameWithoutExt . '.webp';
            $counter = 1;
            while (file_exists($webpPath)) {
                $webpPath = $dir . '/' . $filenameWithoutExt . '_' . $counter . '.webp';
                $counter++;
            }
            $img->toWebp(40)->save($webpPath);
            if (file_exists($webpPath) && file_exists($filePath)) {
                unlink($filePath);
            }
            return basename($webpPath);
        } catch (\Exception $e) {
            throw new \Exception('Conversion failed: ' . $e->getMessage());
        }
    }
}
