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
                            <div class="card-title">NewsUpdate form</div>
                        </div>
                        <form action="{{ route('news-update.update', $newsUpdate->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $newsUpdate->title) }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Description</label>
                                        <textarea name="description" id="description" cols="10" rows="2"
                                            class="form-control @error('description')  is-invalid @enderror">{{ old('description', $newsUpdate->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image">
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png).</small>
                                        @if (!empty($newsUpdate->img_src))
                                            @php
                                                $path = env('IMAGE_UPLOAD_BASE_URL') . env('NEWS_AND_UPDATE');
                                            @endphp
                                            <img src="{{ $path . '/' . $newsUpdate->img_src }}" alt="Current Image"
                                                class="img-fluid mt-2" style="max-width: 50px;" height="50px"
                                                width="50px">
                                        @endif
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image_url" class="form-label">Image URL</label>
                                        <input type="text" class="form-control @error('image_url') is-invalid @enderror"
                                            id="image_url" name="image_url"
                                            value="{{ old('image_url', $newsUpdate->image_url) }}">
                                        @if ($newsUpdate->image_url)
                                            <img src="{{ $newsUpdate->image_url }}" alt="Current Image"
                                                class="img-fluid mt-2" style="max-width: 50px;">
                                        @endif
                                        @error('image_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="link" class="form-label">Link</label>
                                        <input type="text" class="form-control @error('link') is-invalid @enderror"
                                            id="link" name="link" value="{{ old('link', $newsUpdate->link) }}">
                                        @error('link')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="link_title" class="form-label">Link Title</label>
                                        <input type="text" class="form-control @error('link_title') is-invalid @enderror"
                                            id="link_title" name="link_title"
                                            value="{{ old('link_title', $newsUpdate->link_title) }}">
                                        @error('link_title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sort_num" class="form-label">Sort Num</label>
                                        <input type="number" class="form-control @error('sort_num') is-invalid @enderror"
                                            id="sort_num" name="sort_num"
                                            value="{{ old('sort_num', $newsUpdate->sort_num) }}">
                                        @error('sort_num')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="have_popup" class="form-label">Have Popup</label>
                                        <select name="have_popup" id="have_popup"
                                            class="form-select @error('role_id') is-invalid @enderror">
                                            <option value="1"
                                                {{ old('have_popup', $newsUpdate->have_popup) == 1 ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="0"
                                                {{ old('have_popup', $newsUpdate->have_popup) == 0 ? 'selected' : '' }}>No
                                            </option>
                                        </select>
                                        @error('have_popup')
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor.create(document.querySelector("#description"))
            .catch(error => {
                console.error(error);
            });
    });
</script>
