<?php

namespace App\Http\Controllers;

use App\Models\CuratedSection;
use App\Models\InternationalCinema;
use Illuminate\Http\Request;

class InternationalCinemaController extends Controller
{
    public function index()
    {
        $internationalCinemas = InternationalCinema::orderBy('id', 'DESC')->paginate(10);
        $curatedSections = CuratedSection::all();
        return view('international_cinema.index', compact(['internationalCinemas', 'curatedSections']));
    }

    function toggleStatus($id)
    {
        $internationalCinema = InternationalCinema::findOrFail($id);
        $internationalCinema->status = $internationalCinema->status === 1 ? 0 : 1;
        $internationalCinema->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function create ()
    {
        $curatedSections = CuratedSection::all();
        return view('international_cinema.create', compact('curatedSections'));
    }
}
