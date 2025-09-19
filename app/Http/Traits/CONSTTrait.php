<?php

namespace App\Http\Traits;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

trait CONSTTrait
{
    public function locations()
    {
        return [
            'Indian Panorama' => 'images/indian-panorama-cinema',
            'International Cinema' => 'images/cureted-section',
            'Peacock Pdf Destination' => 'images/thePeacock',
            'News And Update' => 'images/news-update/webp',
            'Master Class' => 'images/master-class/webp',
            'Festival Venue' => 'images/festival-venue',
            'Poster' => 'images/thePeacock/poster',
            'Press Release' => 'press_release',
            'Gallery' => 'images/gallery',
            'Cube' => 'images/cube/webp',
            'Jury' => 'images/juries',
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

    public function festivalVenueType()
    {
        return [
            1 => 'Opening & Closing Venue',
            2 => 'Screening Venues',
            3 => 'Open Air Screening',
            4 => 'Masterclasses & In-Conversation Sessions',
        ];
    }
}
