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
                            <div class="card-title">Popup Details</div>
                        </div>
                        <form action="{{ route('newsUpdate.popupUpdate', $newsUpdate->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="pop_up_header" class="form-label">Pop Up Header</label>
                                        <input type="text" name="pop_up_header"
                                            class="form-control @error('pop_up_header') is-invalid @enderror"
                                            value="{{ old('pop_up_header', $newsUpdate->pop_up_header ?? '') }}">
                                        @error('pop_up_header')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="pop_up_content" class="form-label">Pop Up Content</label>
                                        <textarea id="pop_up_content" name="pop_up_content" cols="30" rows="10"
                                            class="form-control @error('pop_up_content') is-invalid @enderror">{{ old('pop_up_content', $newsUpdate->pop_up_content ?? '') }}</textarea>
                                        @error('pop_up_content')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sort_num" class="form-label">Sort Num</label>
                                        <input type="text" name="sort_num"
                                            class="form-control @error('sort_num') is-invalid @enderror"
                                            value="{{ old('sort_num', $newsUpdate->sort_num ?? '') }}">
                                        @error('sort_num')
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

<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor.create(document.querySelector("#pop_up_content"))
            .catch(error => {
                console.error(error);
            });
    });
</script>
