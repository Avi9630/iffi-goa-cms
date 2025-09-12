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
                                        <label for="master_date_id" class="form-label">
                                            <strong>Master Topic</strong>
                                        </label>
                                        <select name="topic_id" id="topic_id"
                                            class="form-select @error('topic_id') is-invalid @enderror">
                                            <option value="" selected>Select Topics</option>
                                            @foreach ($masterTopics as $topic)
                                                <option value="{{ $topic->id }}"
                                                    {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                                    {{ $topic->title }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('moderator.index') }}" class="btn btn-warning">
                                    Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
