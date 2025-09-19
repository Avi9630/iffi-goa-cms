<?php

namespace App\Http\Controllers;

use App\Http\Traits\CONSTTrait;
use App\Models\FestivalVenue;
use App\Services\ConvertToWEBP;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;

class FestivalVenueController extends Controller
{
    use CONSTTrait;

    public function __construct()
    {
        $this->mainUrl = env('IMAGE_UPLOAD_BASE_URL');
        $this->festivalVenuePath = env('FESTIVAL_VENUE');
    }

    function index(Request $request)
    {
        $payload = $request->all();
        $festivalVenues = FestivalVenue::orderBy('id', 'DESC')->get();
        $festivalVenueTypes = $this->festivalVenueType();
        return view('festival_venue.index', compact(['festivalVenues', 'festivalVenueTypes', 'payload']));
    }

    function create()
    {
        $festivalVenueTypes = $this->festivalVenueType();
        return view('festival_venue.create', compact('festivalVenueTypes'));
    }

    function store(Request $request)
    {
        $payload = $request->all();
        $request->validate([
            'venue_type_id' => 'required',
            'festival_venu_name' => 'required|string|max:255',
            'location' => 'nullable',
            'location_name' => 'nullable',
            'year' => 'required',
            'image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'image_url' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        
        $festivalVenue = new FestivalVenue();
        $festivalVenue->venue_type_id = $payload['venue_type_id'];
        $festivalVenue->festival_venu_name = $payload['festival_venu_name'];
        $festivalVenue->location_name = $payload['location_name'] ?? null;
        $festivalVenue->location = $payload['location'] ?? null;
        $festivalVenue->year = $payload['year'];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->festivalVenuePath);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->festivalVenuePath);
            if ($convertInWebp) {
                $festivalVenue->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $festivalVenue->img_url = null;
            }
        } else {
            $festivalVenue->img_url = $payload['image_url'];
            $festivalVenue->img_src = null;
        }
        
        if ($festivalVenue->save()) {
            return redirect()->route('festival-venue.index')->with('success', 'Festival venue addedd successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Failed to create.! Please try again.!!');
        }
    }

    function edit($id)
    {
        $festivalVenue = FestivalVenue::findOrFail($id);
        $festivalVenueTypes = $this->festivalVenueType();
        return view('festival_venue.edit', compact(['festivalVenue', 'festivalVenueTypes']));
    }

    function update(Request $request, $id)
    {
        $payload = $request->all();
        $request->validate([
            'venue_type_id' => 'required',
            'festival_venu_name' => 'required|string|max:255',
            'location' => 'nullable',
            'location_name' => 'nullable',
            'year' => 'required',
            'image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'image_url' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $festivalVenue = FestivalVenue::findOrFail($id);
        $festivalVenue->venue_type_id = $payload['venue_type_id'] ?? $festivalVenue->venue_type_id;
        $festivalVenue->festival_venu_name = $payload['festival_venu_name'] ?? $festivalVenue->festival_venu_name;
        $festivalVenue->year = $payload['year'] ?? $festivalVenue->year;
        $festivalVenue->location = isset($payload['location']) && !empty($payload['location']) ? $payload['location'] : '';
        $festivalVenue->location_name = isset($payload['location_name']) && !empty($payload['location_name']) ? $payload['location_name'] : null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $originalFilename = $file->getClientOriginalName();
            app(ExternalApiService::class)->postData($file, $this->festivalVenuePath);
            $convertInWebp = app(ConvertToWEBP::class)->convert($request->file('image'), $this->festivalVenuePath);
            if ($convertInWebp) {
                $festivalVenue->img_src = pathinfo($originalFilename, PATHINFO_FILENAME) . '.webp';
                $festivalVenue->img_url = null;
            }
        } else {
            $festivalVenue->img_url = $payload['image_url'];
            $festivalVenue->img_src = null;
        }

        if ($festivalVenue->save()) {
            return redirect()->route('festival-venue.index')->with('success', 'Festival Venue updated successfully.!!');
        } else {
            return redirect()->back()->with('error', 'Soemthing went wrong.! Please try again.!!');
        }
    }

    function toggle($id)
    {
        $festivalVenue = FestivalVenue::findOrFail($id);
        $festivalVenue->status = $festivalVenue->status === 1 ? 0 : 1;
        $festivalVenue->save();
        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    function destroy($id)
    {
        $festivalVenue = FestivalVenue::findOrFail($id);
        $festivalVenue->delete();
        return redirect()->route('festival-venue.index')->with('danger', 'Festival venue deleted successfully.!!');
    }
}
