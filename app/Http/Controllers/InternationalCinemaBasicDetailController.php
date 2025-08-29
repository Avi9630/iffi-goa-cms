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

    function search(Request $request)
    {
        $payload = $request->all();
        $searchTerm = $request->input('search');
        $icBasicDetails = InternationalCinemaBasicDetail::where('cinema_id',$searchTerm)
            ->orWhere('director', 'LIKE', "%{$searchTerm}%")
            ->orWhere('producer', 'LIKE', "%{$searchTerm}%")
            ->orWhere('screenplay', 'LIKE', "%{$searchTerm}%")
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('international_cinema_basic_detail.index', compact('icBasicDetails'));
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $icBasicDetail = InternationalCinemaBasicDetail::where('cinema_id', $payload['cinema_id'])->first();
        if ($icBasicDetail) {
            $icBasicDetail['director'] = $payload['director'] ?? $icBasicDetail['director'];
            $icBasicDetail['producer'] = $payload['producer'] ?? $icBasicDetail['producer'];
            $icBasicDetail['screenplay'] = $payload['screenplay'] ?? $icBasicDetail['screenplay'];
            $icBasicDetail['co_screenplay'] = $payload['co_screenplay'] ?? $icBasicDetail['co_screenplay'];
            $icBasicDetail['cinematographer'] = $payload['cinematographer'] ?? $icBasicDetail['cinematographer'];
            $icBasicDetail['editor'] = $payload['editor'] ?? $icBasicDetail['editor'];
            $icBasicDetail['cast'] = $payload['cast'] ?? $icBasicDetail['cast'];
            $icBasicDetail['dop'] = $payload['dop'] ?? $icBasicDetail['dop'];
            $icBasicDetail['other_details'] = $payload['other_details'] ?? $icBasicDetail['other_details'];
            $icBasicDetail['synopsis'] = $payload['synopsis'] ?? $icBasicDetail['synopsis'];
            $icBasicDetail['director_bio'] = $payload['director_bio'] ?? $icBasicDetail['director_bio'];
            $icBasicDetail['producer_bio'] = $payload['producer_bio'] ?? $icBasicDetail['producer_bio'];
            $icBasicDetail['sales_agent'] = $payload['sales_agent'] ?? $icBasicDetail['sales_agent'];
            $icBasicDetail['award'] = $payload['award'] ?? $icBasicDetail['award'];
            $icBasicDetail['writer'] = $payload['writer'] ?? $icBasicDetail['writer'];
            $icBasicDetail['trailer_link'] = $payload['trailer_link'] ?? $icBasicDetail['trailer_link'];
            $icBasicDetail['official_selection'] = $payload['official_selection'] ?? $icBasicDetail['official_selection'];
            $icBasicDetail['best_film_award'] = $payload['best_film_award'] ?? $icBasicDetail['best_film_award'];
            $icBasicDetail['director_and_producer'] = $payload['director_and_producer'] ?? $icBasicDetail['director_and_producer'];
            $icBasicDetail['original_title'] = $payload['original_title'] ?? $icBasicDetail['original_title'];
            $icBasicDetail['co_produced'] = $payload['co_produced'] ?? $icBasicDetail['co_produced'];
            $icBasicDetail['festivals'] = $payload['festivals'] ?? $icBasicDetail['festivals'];
            $icBasicDetail['drama'] = $payload['drama'] ?? $icBasicDetail['drama'];
            $icBasicDetail['history'] = $payload['history'] ?? $icBasicDetail['history'];
            $icBasicDetail['nomination'] = $payload['nomination'] ?? $icBasicDetail['nomination'];
            $icBasicDetail['festival_history'] = $payload['festival_history'] ?? $icBasicDetail['festival_history'];
            $icBasicDetail['link_trailer'] = $payload['link_trailer'] ?? $icBasicDetail['link_trailer'];
            $icBasicDetail['tags'] = $payload['tags'] ?? $icBasicDetail['tags'];
            $icBasicDetail['sales'] = $payload['sales'] ?? $icBasicDetail['sales'];
            $icBasicDetail['instagram'] = $payload['instagram'] ?? $icBasicDetail['instagram'];
            $icBasicDetail['twitter'] = $payload['twitter'] ?? $icBasicDetail['twitter'];
            $icBasicDetail['facebook'] = $payload['facebook'] ?? $icBasicDetail['facebook'];
            $icBasicDetail->save();
            return redirect()->route('ic-basic-detail.index')->with('success', 'Basic details updated successfully.!!');
        } else {
            $icBasicDetails = InternationalCinemaBasicDetail::create($payload);
            return redirect()->route('ic-basic-detail.index')->with('success', 'Basic details updated successfully.!!');
        }
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
            $icBasicDetail['director'] = $payload['director'] ?? $icBasicDetail['director'];
            $icBasicDetail['producer'] = $payload['producer'] ?? $icBasicDetail['producer'];
            $icBasicDetail['screenplay'] = $payload['screenplay'] ?? $icBasicDetail['screenplay'];
            $icBasicDetail['cinematographer'] = $payload['cinematographer'] ?? $icBasicDetail['cinematographer'];
            $icBasicDetail['editor'] = $payload['editor'] ?? $icBasicDetail['editor'];
            $icBasicDetail['cast'] = $payload['cast'] ?? $icBasicDetail['cast'];
            $icBasicDetail['dop'] = $payload['dop'] ?? $icBasicDetail['dop'];
            $icBasicDetail['other_details'] = $payload['other_details'] ?? $icBasicDetail['other_details'];
            $icBasicDetail['synopsis'] = $payload['synopsis'] ?? $icBasicDetail['synopsis'];
            $icBasicDetail['director_bio'] = $payload['director_bio'] ?? $icBasicDetail['director_bio'];
            $icBasicDetail['producer_bio'] = $payload['producer_bio'] ?? $icBasicDetail['producer_bio'];
            $icBasicDetail['sales_agent'] = $payload['sales_agent'] ?? $icBasicDetail['sales_agent'];
            $icBasicDetail['award'] = $payload['award'] ?? $icBasicDetail['award'];
            $icBasicDetail['writer'] = $payload['writer'] ?? $icBasicDetail['writer'];
            $icBasicDetail['trailer_link'] = $payload['trailer_link'] ?? $icBasicDetail['trailer_link'];
            $icBasicDetail['official_selection'] = $payload['official_selection'] ?? $icBasicDetail['official_selection'];
            $icBasicDetail['best_film_award'] = $payload['best_film_award'] ?? $icBasicDetail['best_film_award'];
            $icBasicDetail['director_and_producer'] = $payload['director_and_producer'] ?? $icBasicDetail['director_and_producer'];
            $icBasicDetail['original_title'] = $payload['original_title'] ?? $icBasicDetail['original_title'];
            $icBasicDetail['co_produced'] = $payload['co_produced'] ?? $icBasicDetail['co_produced'];
            $icBasicDetail['festivals'] = $payload['festivals'] ?? $icBasicDetail['festivals'];
            $icBasicDetail['drama'] = $payload['drama'] ?? $icBasicDetail['drama'];
            $icBasicDetail['history'] = $payload['history'] ?? $icBasicDetail['history'];
            $icBasicDetail['nomination'] = $payload['nomination'] ?? $icBasicDetail['nomination'];
            $icBasicDetail['festival_history'] = $payload['festival_history'] ?? $icBasicDetail['festival_history'];
            $icBasicDetail['link_trailer'] = $payload['link_trailer'] ?? $icBasicDetail['link_trailer'];
            $icBasicDetail['tags'] = $payload['tags'] ?? $icBasicDetail['tags'];
            $icBasicDetail['sales'] = $payload['sales'] ?? $icBasicDetail['sales'];
            $icBasicDetail['instagram'] = $payload['instagram'] ?? $icBasicDetail['instagram'];
            $icBasicDetail['twitter'] = $payload['twitter'] ?? $icBasicDetail['twitter'];
            $icBasicDetail['facebook'] = $payload['facebook'] ?? $icBasicDetail['facebook'];
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
