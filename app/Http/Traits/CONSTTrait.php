<?php

namespace App\Http\Traits;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

trait CONSTTrait
{
    public function locations()
    {
        return [
            'Jury' => 'images/jury/indian-panorama-jury',
            'Poster' => 'images/thePeacock/poster',
            'Master Class' => 'images/master-class/webp',
            'Indian Panorama' => 'images/indian-panorama-cinema',
            'International Cinema' => 'images/cureted-section',
            'Peacock Pdf Destination' => 'images/thePeacock',
            'News And Update' => 'images/news-update/webp',
            'Cube Destination' => 'images/cube/webp',
            'Press Release' => 'press_release',
            'Gallery' => 'images/gallery',
        ];
    }

    public function juryType()
    {
        return [
            1 => 'Indian Panorama-(Feature)',
            2 => 'Indian Panorama-(Non-Feature)',
            3 => 'Best Web Series-(Jury)',
            4 => 'Best Web Series-(Preview Committee)',
            5 => 'CMOT-(Selection Jury)',
            6 => 'CMOT-(Grand-Jury)',
        ];
    }
}
