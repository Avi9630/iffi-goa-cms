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
                            <div class="card-title">International Cinema Form</div>
                        </div>
                        <form action="{{ route('international-cinema.update', $internationalCinema->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="curated_section_id" class="form-label"><strong>Curated
                                                Sections</strong></label>
                                        <select name="curated_section_id" id="curated_section_id"
                                            class="form-select @error('curated_section_id') is-invalid @enderror">
                                            <option value="" selected>Select Curated Sections</option>
                                            @foreach ($curatedSections as $curatedSection)
                                                <option value="{{ $curatedSection->id }}"
                                                    {{ $curatedSection->id == $internationalCinema['curated_section_id'] ? 'selected' : '' }}>
                                                    {{ $curatedSection->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('curated_section_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $internationalCinema->title) }}"
                                            placeholder="Enter title." />
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                            id="slug" name="slug" value="{{ old('slug', $internationalCinema->slug) }}"
                                            placeholder="like:- international-cinema" />
                                        @error('slug')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- <div class="col-md-6 mb-3">
                                        <label for="award" class="form-label">Award</label>
                                        <input type="text" class="form-control @error('award') is-invalid @enderror"
                                            id="award" name="award" placeholder="Enter award" />
                                        @error('award')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-md-6 mb-3">
                                        <label for="directed_by" class="form-label">Directed By</label>
                                        <input type="text"
                                            class="form-control @error('directed_by') is-invalid @enderror" id="directed_by"
                                            name="directed_by" value="{{ old('directed_by', $internationalCinema->directed_by) }}"
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
                                            value="{{ old('country_of_origin', $internationalCinema->country_of_origin) }}" placeholder="Enter country of origin" />
                                        @error('country_of_origin')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <input type="text" class="form-control @error('language') is-invalid @enderror"
                                            id="language" name="language" value="{{ old('language', $internationalCinema->language) }}"
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
                                        @if ($internationalCinema->img_url)
                                            <img src="{{ $internationalCinema->img_url }}" alt="Current Image" class="img-fluid mt-2"
                                                style="max-width: 50px;">
                                        @endif
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image_url" class="form-label">Image URL</label>
                                        <input type="text" class="form-control @error('image_url') is-invalid @enderror"
                                            id="image_url" name="image_url" value="{{ old('image_url', $internationalCinema->img_url) }}">
                                        @error('image_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- OPTIONAL --}}
                                    {{-- <div class="col-md-6 mb-3">
                                        <label for="section" class="form-label">Section</label>
                                        <input type="text" class="form-control @error('section') is-invalid @enderror"
                                            id="section" name="section" value="{{ old('section') }}" placeholder="Enter section" />
                                        @error('section')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sub_section" class="form-label">Sub Section</label>
                                        <input type="text"
                                            class="form-control @error('sub_section') is-invalid @enderror"
                                            id="sub_section" name="sub_section" value="{{ old('sub_section') }}" placeholder="Enter sub-section"/>
                                        @error('sub_section')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ $internationalCinema->year == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ $internationalCinema->year == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ $internationalCinema->year == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ $internationalCinema->year == 2022 ? 'selected' : '' }}>2022
                                            </option>
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="award_year" class="form-label">Award Year</label>
                                        <input type="number"
                                            class="form-control @error('award_year') is-invalid @enderror" id="award_year"
                                            name="award_year" value="{{ old('award_year', $internationalCinema->award_year) }}" placeholder="Like- 2025"
                                            value="{{ old('award_year') }}" />
                                        @error('award_year')
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
