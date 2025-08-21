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
                        <form action="{{ route('peacock.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">PDF</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" value="{{ old('image') }}"
                                            placeholder="Only PDF allowed.">
                                        <small class="form-text text-muted">Upload an image file (pdf).</small>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="poster" class="form-label">Poster</label>
                                        <input type="file" class="form-control @error('poster') is-invalid @enderror"
                                            id="poster" name="poster" value="{{ old('poster') }}">
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png,
                                            webp).</small>
                                        @error('poster')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ old('year') == 2025 ? 'selected' : '' }}>2025</option>
                                            <option value="2024" {{ old('year') == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ old('year') == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ old('year') == 2022 ? 'selected' : '' }}>2022
                                            </option>
                                            <option value="2021" {{ old('year') == 2021 ? 'selected' : '' }}>2021
                                            </option>
                                            <option value="2020" {{ old('year') == 2020 ? 'selected' : '' }}>2020
                                            </option>
                                            <option value="2019" {{ old('year') == 2019 ? 'selected' : '' }}>2019
                                            </option>
                                            <option value="2018" {{ old('year') == 2018 ? 'selected' : '' }}>2018
                                            </option>
                                            <option value="2017" {{ old('year') == 2017 ? 'selected' : '' }}>2017
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
