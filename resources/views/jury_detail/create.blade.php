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
                            <div class="card-title">Jury Details</div>
                        </div>
                        <form action="{{ route('jury-detail.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="official_selection_id" class="form-label">
                                            <strong>Jury Type</strong>
                                        </label>
                                        <select name="jury_type_id" id="jury_type_id"
                                            class="form-select @error('jury_type_id') is-invalid @enderror">
                                            <option value="" selected>Select Jury Type</option>
                                            @foreach ($juryTypes as $key => $juryType)
                                                <option name="jury_type_id" value="{{ $key }}"
                                                    {{ old('jury_type_id') == $key ? 'selected' : '' }}>
                                                    {{ $juryType }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jury_type_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text"
                                            class="form-control @error('designation') is-invalid @enderror" id="designation"
                                            name="designation" value="{{ old('designation') }}">
                                        @error('designation')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="is_chairperson" class="form-label">Chairperson</label>
                                        <div class="form-check">
                                            <input type="checkbox"
                                                class="form-check-input @error('is_chairperson') is-invalid @enderror"
                                                id="is_chairperson" name="is_chairperson" value="1"
                                                {{ old('is_chairperson') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_chairperson">IsChairperson</label>
                                        </div>
                                        @error('is_chairperson')
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
                                            id="image_url" name="image_url" value="{{ old('image_url') }}">
                                        @error('image_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" id="description" cols="10" rows="5"
                                            class="form-control @error('description') is-invalid @enderror">
                                            {{ old('description') }}
                                        </textarea>
                                        @error('description')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ old('year') == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ old('year') == 2024 ? 'selected' : '' }}>2024
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
                                <a href={{ route('jury-detail.index') }} class="btn btn-sm btn-warning btn-flat ">
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
