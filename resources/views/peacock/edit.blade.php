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
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" value="{{ old('image', $peacock->image_url) }}">
                                        <small class="form-text text-muted">Upload an image file (pdf).</small>
                                        @if ($peacock->image_url)
                                            <iframe src="{{ $peacock->image_url }}" width="100%" height="100px"></iframe>
                                        @endif
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="poster" class="form-label">Poster</label>
                                        <input type="file" class="form-control @error('poster') is-invalid @enderror"
                                            id="poster" name="poster" value="{{ old('poster', $peacock->poster_url) }}">
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png,
                                            gif).</small>
                                        @if ($peacock->poster_url)
                                            <img src="{{ $peacock->poster_url }}" alt="Current Image" class="img-fluid mt-2"
                                                style="max-width: 100px;">
                                        @endif
                                        @error('poster')
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
