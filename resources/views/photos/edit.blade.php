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
                            <div class="card-title">Photo form</div>
                        </div>
                        <form action="{{ route('photo.update', $photo->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label"><strong>Category</strong></label>
                                        <select name="category_id" id="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror">
                                            <option value="" selected>Select Category</option>
                                            @foreach ($photoCategories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $category->id == $photo['category_id'] ? 'selected' : '' }}>
                                                    {{ $category->category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" />
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png,
                                            gif).</small>
                                        @if ($photo->img_url)
                                            <img src="{{ $photo->img_url }}" alt="Current Image" class="img-fluid mt-2"
                                                style="max-width: 50px;">
                                        @endif
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="img_caption" class="form-label">Image Caption</label>
                                        <input type="text"
                                            class="form-control @error('img_caption') is-invalid @enderror" id="img_caption"
                                            name="img_caption" value="{{ old('image_caption', $photo->img_caption) }}" />
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
                                            <option value="2024" {{ $photo->year == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2025" {{ $photo->year == 2025 ? 'selected' : '' }}>2025
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
