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
                        <form action="{{ route('international-cinema.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label"><strong>Curated
                                                Sections</strong></label>
                                        <select name="category_id" id="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror">
                                            <option value="" selected>Select Curated Sections</option>
                                            @foreach ($curatedSections as $curatedSection)
                                                <option value="{{ $curatedSection->id }}"
                                                    {{ old('category_id') == $curatedSection->id ? 'selected' : '' }}>
                                                    {{ $curatedSection->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" />
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                            id="slug" name="slug" />
                                        @error('slug')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="award" class="form-label">Award</label>
                                        <input type="text" class="form-control @error('award') is-invalid @enderror"
                                            id="award" name="award" />
                                        @error('award')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="directed_by" class="form-label">Directed By</label>
                                        <input type="text" class="form-control @error('directed_by') is-invalid @enderror"
                                            id="directed_by" name="directed_by" />
                                        @error('directed_by')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="country_of_origin" class="form-label">Country Of Origin</label>
                                        <input type="text" class="form-control @error('country_of_origin') is-invalid @enderror"
                                            id="country_of_origin" name="country_of_origin" />
                                        @error('country_of_origin')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <input type="text" class="form-control @error('language') is-invalid @enderror"
                                            id="language" name="language" />
                                        @error('language')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" />
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png,
                                            gif).</small>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{--
                                    <div class="col-md-6 mb-3">
                                        <label for="img_caption" class="form-label">Image Caption</label>
                                        <input type="text" class="form-control @error('img_caption') is-invalid @enderror"
                                            id="img_caption" name="img_caption"  value="{{ old('image_caption') }}"/>
                                        @error('img_caption')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="video_url" class="form-label">Video URL</label>
                                        <input type="text" class="form-control @error('video_url') is-invalid @enderror"
                                            id="video_url" name="video_url" placeholder="Enter video URL"
                                            value="{{ old('video_url') }}" />
                                        @error('video_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2024" {{ old('year') == 2024 ? 'selected' : '' }}>2024</option>
                                            <option value="2025" {{ old('year') == 2025 ? 'selected' : '' }}>2025</option>
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div> --}}
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
