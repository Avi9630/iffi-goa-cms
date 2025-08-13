<?php

namespace App\Http\Controllers;

use App\Models\InternationalCinemaBasicDetail;
use Illuminate\Http\Request;

class InternationalCinemaBasicDetailController extends Controller
{
    function index()
    {
        $icBasicDetails = InternationalCinemaBasicDetail::orderBy('id', 'DESC')->paginate(10);
        return view('international_cinema_basic_detail.index', compact('icBasicDetails'));
    }

    function edit($id)
    {
        $icBasicDetail = InternationalCinemaBasicDetail::findOrFail($id);
        return view('international_cinema_basic_detail.edit', compact('icBasicDetail'));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $icBasicDetail = InternationalCinemaBasicDetail::findOrFail($id);
        if ($icBasicDetail) {
            // $internationalCinemaBasicDetail = new InternationalCinemaBasicDetail();
            $icBasicDetail['director'] = $payload['director'] ?? null;
            $icBasicDetail['producer'] = $payload['producer'] ?? null;
            $icBasicDetail['screenplay'] = $payload['screenplay'] ?? null;
            $icBasicDetail['cinematographer'] = $payload['cinematographer'] ?? null;
            $icBasicDetail['editor'] = $payload['editor'] ?? null;
            $icBasicDetail['cast'] = $payload['cast'] ?? null;
            $icBasicDetail['dop'] = $payload['dop'] ?? null;
            $icBasicDetail['other_details'] = $payload['other_details'] ?? null;
            $icBasicDetail['synopsis'] = $payload['synopsis'] ?? null;
            $icBasicDetail['director_bio'] = $payload['director_bio'] ?? null;
            $icBasicDetail['producer_bio'] = $payload['producer_bio'] ?? null;
            $icBasicDetail['sales_agent'] = $payload['sales_agent'] ?? null;
            $icBasicDetail['award'] = $payload['award'] ?? null;
            $icBasicDetail['writer'] = $payload['writer'] ?? null;
            $icBasicDetail['trailer_link'] = $payload['trailer_link'] ?? null;
            $icBasicDetail['official_selection'] = $payload['official_selection'] ?? null;
            $icBasicDetail['best_film_award'] = $payload['best_film_award'] ?? null;
            $icBasicDetail['director_and_producer'] = $payload['director_and_producer'] ?? null;
            $icBasicDetail['original_title'] = $payload['original_title'] ?? null;
            $icBasicDetail['co_produced'] = $payload['co_produced'] ?? null;
            $icBasicDetail['festivals'] = $payload['festivals'] ?? null;
            $icBasicDetail['drama'] = $payload['drama'] ?? null;
            $icBasicDetail['history'] = $payload['history'] ?? null;
            $icBasicDetail['nomination'] = $payload['nomination'] ?? null;
            $icBasicDetail->save();
            return redirect()->route('ic-basic-detail.index')->with('success', 'Basic details updated successfully.!!');
        } else {
            return redirect()->back()->with('warning', 'Something went wrong.!!');
        }
    }

    function show($id)
    {
        $icBasicDetail = InternationalCinemaBasicDetail::findOrFail($id);
        return view('international_cinema_basic_detail.show', compact('icBasicDetail'));
    }

    function toggleStatus($id)
    {
        $icBasicDetail = InternationalCinemaBasicDetail::findOrFail($id);
        $icBasicDetail->status = $icBasicDetail->status === 1 ? 0 : 1;
        $icBasicDetail->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function destroy($id)
    {
        $icBasicDetail = InternationalCinemaBasicDetail::findOrFail($id);
        $icBasicDetail->delete();
        return redirect()->route('ic-basic-detail.index')->with('danger', 'Entry deleted successfully.!!');
    }
}
