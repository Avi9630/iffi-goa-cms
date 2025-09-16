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
}
