@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
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

    <div class="app-content mt-2">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Festival Venue</div>
                        </div>
                        <form action="{{ route('festival-venue.update', $festivalVenue->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="venue_type_id" class="form-label">
                                            <strong>Festival Venue Type</strong>
                                        </label>
                                        <select name="venue_type_id" id="venue_type_id"
                                            class="form-select @error('venue_type_id') is-invalid @enderror">
                                            <option value="" selected>Select Jury Type</option>
                                            @foreach ($festivalVenueTypes as $key => $type)
                                                <option name="venue_type_id" value="{{ $key }}"
                                                    {{ $key == $festivalVenue['venue_type_id'] ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('venue_type_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="festival_venu_name" class="form-label">festival_venu_name</label>
                                        <input type="text"
                                            class="form-control @error('festival_venu_name') is-invalid @enderror"
                                            id="festival_venu_name" name="festival_venu_name"
                                            value="{{ old('festival_venu_name', $festivalVenue->festival_venu_name) }}">
                                        @error('festival_venu_name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="location_name" class="form-label">Location Name</label>
                                        <input type="text"
                                            class="form-control @error('location_name') is-invalid @enderror"
                                            id="location_name" name="location_name" value="{{ old('location_name', $festivalVenue->location_name) }}">
                                        @error('location_name')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="location" class="form-label">Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location" value="{{ old('location', $festivalVenue->location) }}">
                                        @error('location')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image">
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png,
                                            webp).</small>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image_url" class="form-label">Image URL</label>
                                        <input type="text" class="form-control @error('image_url') is-invalid @enderror"
                                            id="image_url" name="image_url"
                                            value="{{ old('image_url', $festivalVenue->img_url) }}">
                                        @error('image_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ $festivalVenue->year == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ $festivalVenue->year == 2024 ? 'selected' : '' }}>2024
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
                                <a href={{ route('festival-venue.index') }} class="btn btn-sm btn-warning btn-flat ">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
