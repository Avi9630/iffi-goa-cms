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
                            <div class="card-title">Indian Panorama</div>
                        </div>
                        <form action="{{ route('indian-panorama.update', $indianPanorama->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="curated_section_id" class="form-label">
                                            <strong>Curated Sections</strong>
                                        </label>
                                        <select name="official_selection_id" id="official_selection_id"
                                            class="form-select @error('official_selection_id') is-invalid @enderror">
                                            <option value="" selected>Select Curated Sections</option>
                                            @foreach ($IPOfficialSelections as $IPOfficialSelection)
                                                <option value="{{ $IPOfficialSelection->id }}"
                                                    {{ $IPOfficialSelection->id == $indianPanorama['official_selection_id'] ? 'selected' : '' }}>
                                                    {{ $IPOfficialSelection->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('official_selection_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $indianPanorama->title) }}"
                                            placeholder="Enter title." />
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="directed_by" class="form-label">Directed By</label>
                                        <input type="text"
                                            class="form-control @error('directed_by') is-invalid @enderror" id="directed_by"
                                            name="directed_by" value="{{ old('directed_by', $indianPanorama->directed_by) }}"
                                            placeholder="Directed by" />
                                        @error('directed_by')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="country_of_origin" class="form-label">Country Of Origin</label>
                                        <input type="text"
                                            class="form-control @error('country_of_origin') is-invalid @enderror"
                                            id="country_of_origin" name="country_of_origin"
                                            value="{{ old('country_of_origin', $indianPanorama->country_of_origin) }}" placeholder="Enter country of origin" />
                                        @error('country_of_origin')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <input type="text" class="form-control @error('language') is-invalid @enderror"
                                            id="language" name="language" value="{{ old('language', $indianPanorama->language) }}"
                                            placeholder="Enter language" />
                                        @error('language')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" />
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png).</small>
                                        @if ($indianPanorama->img_url)
                                            <img src="{{ $indianPanorama->img_url }}" alt="Current Image" class="img-fluid mt-2"
                                                style="max-width: 50px;">
                                        @endif
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ $indianPanorama->year == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ $indianPanorama->year == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ $indianPanorama->year == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ $indianPanorama->year == 2022 ? 'selected' : '' }}>2022
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
    </div>
@endsection
