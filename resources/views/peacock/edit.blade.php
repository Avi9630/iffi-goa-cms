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
                            <div class="card-title">Peacock Form</div>
                        </div>
                        <form action="{{ route('peacock.update', $peacock->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $peacock->title) }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="poster" class="form-label">Poster</label>
                                        <input type="file" class="form-control @error('poster') is-invalid @enderror"
                                            id="poster" name="poster">
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png,
                                            webp).</small>
                                        @error('poster')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="poster_url" class="form-label">Poster URL</label>
                                        <input type="text" class="form-control @error('poster_url') is-invalid @enderror"
                                            id="poster_url" name="poster_url" value="{{ old('poster_url',$peacock->poster_url) }}">
                                        @error('poster_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="pdf" class="form-label">PDF</label>
                                        <input type="file" class="form-control @error('pdf') is-invalid @enderror"
                                            id="pdf" name="pdf" placeholder="Only PDF allowed.">
                                        <small class="form-text text-muted">Upload an pdf file.</small>
                                        @error('pdf')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="pdf_url" class="form-label">PDF URL</label>
                                        <input type="text" class="form-control @error('pdf_url') is-invalid @enderror"
                                            id="pdf_url" name="pdf_url" value="{{ old('pdf_url',$peacock->image_url) }}">
                                        @error('pdf_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ $peacock->year == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ $peacock->year == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ $peacock->year == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ $peacock->year == 2022 ? 'selected' : '' }}>2022
                                            </option>
                                            <option value="2021" {{ $peacock->year == 2021 ? 'selected' : '' }}>2021
                                            </option>
                                            <option value="2020" {{ $peacock->year == 2020 ? 'selected' : '' }}>2020
                                            </option>
                                            <option value="2019" {{ $peacock->year == 2019 ? 'selected' : '' }}>2019
                                            </option>
                                            <option value="2018" {{ $peacock->year == 2018 ? 'selected' : '' }}>2018
                                            </option>
                                            <option value="2017" {{ $peacock->year == 2017 ? 'selected' : '' }}>2017
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
