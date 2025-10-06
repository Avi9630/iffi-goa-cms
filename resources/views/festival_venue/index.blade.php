@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Festival Venue</strong></h3>
                </div>
                <div class="col-sm-6">
                    <span>
                        <h4 class="alert-danger"></h4>
                    </span>
                    @foreach (['success', 'info', 'danger', 'warning'] as $msg)
                        @if (Session::has($msg))
                            <div id="flash-message" class="alert alert-{{ $msg }}" role="alert">
                                {{ Session::get($msg) }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="app-content mt-2">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Search</div>
                        </div>
                        <form action="{{ route('juryDetail.search') }}" method="GET">
                            @csrf @method('GET')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="jury_type_id" class="form-label">
                                            <strong>Jury Type</strong>
                                        </label>
                                        <select name="jury_type_id" id="jury_type_id"
                                            class="form-select @error('jury_type_id') is-invalid @enderror">
                                            <option value="" selected>Select Jury Type</option>
                                            @foreach ($juryTypes as $key => $juryType)
                                                <option name="jury_type_id" value="{{ $key }}"
                                                    {{ isset($payload['jury_type_id']) && $payload['jury_type_id'] == $key ? 'selected' : '' }}>
                                                    {{ $juryType }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jury_type_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label"><strong>Name</strong></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ isset($payload['name']) ? $payload['name'] : '' }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025"
                                                {{ isset($payload['year']) && $payload['year'] == 2025 ? 'selected' : '' }}>
                                                2025
                                            </option>
                                            <option value="2024"
                                                {{ isset($payload['year']) && $payload['year'] == 2024 ? 'selected' : '' }}>
                                                2024
                                            </option>
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href={{ route('festival-venue.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Festival Venue
                                </a>
                                <a href={{ route('festival-venue.index') }} class="btn btn-sm btn-warning btn-flat ">
                                    Reset
                                </a>
                            </h3>
                        </div>

                        <div class="card-body">
                            @foreach ($festivalVenueTypes as $key => $type)
                                <h4 class="mt-4">{{ $type }}</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.Nom</th>
                                            <th>Venue Type</th>
                                            <th>Festival Venue Name</th>
                                            <th>Location Name</th>
                                            <th>Location</th>
                                            <th>Image</th>
                                            <th>Year</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($festivalVenues->where('venue_type_id', $key)->sortBy('id') as $festivalVenue)
                                            <tr class="align-middle">
                                                <td>{{ $festivalVenue->id }}</td>
                                                <td>
                                                    {{ $festivalVenue->venue_type_id == 1
                                                        ? 'Opening & Closing Venue'
                                                        : ($festivalVenue->venue_type_id == 2
                                                            ? 'Screening Venues'
                                                            : ($festivalVenue->venue_type_id == 3
                                                                ? 'Open Air Screening'
                                                                : ($festivalVenue->venue_type_id == 4
                                                                    ? 'Masterclasses & In-Conversation Sessions'
                                                                    : ''))) }}
                                                </td>
                                                <td>{{ $festivalVenue->festival_venu_name }}</td>
                                                <td>{{ $festivalVenue->location_name }}</td>
                                                <td>{{ $festivalVenue->location }}</td>
                                                <td>
                                                    @php
                                                        $path =
                                                            env('IMAGE_UPLOAD_BASE_URL') .
                                                            env('FESTIVAL_VENUE');
                                                    @endphp
                                                    @if (!empty($festivalVenue->img_src))
                                                        <img src="{{ $path . '/' . $festivalVenue->img_src }}"
                                                            alt="Current Image" class="img-fluid mt-2"
                                                            style="max-width: 100px;">
                                                    @elseif(!empty($festivalVenue->img_url))
                                                        <img src="{{ $festivalVenue->img_url }}" alt="Current Image"
                                                            class="img-fluid mt-2" style="max-width: 100px;">
                                                    @else
                                                        <p>images not uploaded</p>
                                                    @endif
                                                </td>
                                                <td>{{ $festivalVenue->year }}</td>
                                                <td>
                                                    <form action="{{ route('festivalVenue.toggle', $festivalVenue->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="btn {{ $festivalVenue->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                            {{ $festivalVenue->status === 1 ? 'Enabled' : 'Disabled' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td style="white-space: nowrap;">
                                                    <a href="{{ route('festival-venue.edit', $festivalVenue->id) }}"
                                                        class="btn btn-info btn-sm">Edit</a>
                                                    @can('delete')
                                                        <form action="{{ route('festival-venue.destroy', $festivalVenue->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
