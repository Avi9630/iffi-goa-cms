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
                            <div class="card-title">Press Release Form</div>
                        </div>
                        <form action="{{ route('press-release.update', $pressRelease->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="pr_category_id" class="form-label"><strong>Category</strong></label>
                                        <select name="pr_category_id" id="pr_category_id"
                                            class="form-select @error('pr_category_id') is-invalid @enderror">
                                            <option value="" selected>Select Category</option>
                                            <option value="1" {{ $pressRelease->pr_category_id == 1 ? 'selected' : '' }}>
                                                PRESS RELEASES BY PIB
                                            </option>
                                            <option value="2" {{ $pressRelease->pr_category_id == 2 ? 'selected' : '' }}>
                                                MEDIA COVERAGE (NON PIB)
                                            </option>
                                        </select>
                                        @error('pr_category_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $pressRelease->title) }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image">
                                        <div>
                                            <a href="{{ !empty($pressRelease->image_url)
                                                ? asset('press_release/' . basename($pressRelease->image_url))
                                                : (!empty($pressRelease->link)
                                                    ? $pressRelease->link
                                                    : asset('press_release/' . $pressRelease->img_src)) }}"
                                                class="btn btn-primary" target="_blank">
                                                View PDF
                                            </a>
                                        </div>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="link" class="form-label">Link</label>
                                        <input type="text" class="form-control @error('link') is-invalid @enderror"
                                            id="link" name="link" value="{{ $pressRelease->link }}">
                                        @error('link')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ $pressRelease->year == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ $pressRelease->year == 2024 ? 'selected' : '' }}>2024
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
