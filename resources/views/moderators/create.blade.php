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
                            <div class="card-title">Moderators form</div>
                        </div>
                        <form action="{{ route('moderator.store') }}" method="POST" enctype="multipart/form-data">@csrf
                            @method('POST')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="topic_id" class="form-label">Topic ID</label>
                                        <input type="number" class="form-control @error('topic_id') is-invalid @enderror"
                                            id="topic_id" name="topic_id" value="{{ old('topic_id', $masterTopic->id) }}"
                                            placeholder="Enter topic_id." readonly />
                                        @error('topic_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="moderator_name" class="form-label">Moderator Name</label>
                                        <input type="text"
                                            class="form-control @error('moderator_name') is-invalid @enderror"
                                            id="moderator_name" name="moderator_name" value="{{ old('moderator_name') }}"
                                            placeholder="Enter moderator_name." />
                                        @error('moderator_name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="moderator_detail" class="form-label">Moderator Detail</label>
                                        <textarea name="moderator_detail" id="moderator_detail" cols="10" rows="5"
                                            class="form-control @error('moderator_detail') is-invalid @enderror" value="{{ old('moderator_detail') }}">                                            
                                        </textarea>
                                        @error('moderator_detail')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" />
                                        <small class="form-text text-muted">Upload an image file (webp).</small>
                                        @error('image')
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
