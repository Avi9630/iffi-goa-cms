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
                            <div class="card-title">Master Class Form</div>
                        </div>
                        <form action="{{ route('master-class.store') }}" method="POST">
                            @csrf
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
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                            id="date" name="date" value="{{ old('date') }}"
                                            placeholder="Enter date." min="2025-11-20" max="2025-11-30" />
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="start_time" class="form-label">Start time</label>
                                        <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                            id="start_time" name="start_time" value="{{ old('start_time') }}"
                                            placeholder="Start time" />
                                        @error('start_time')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="end_time" class="form-label">End Time</label>
                                        <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                            id="end_time" name="end_time" value="{{ old('end_time') }}"
                                            placeholder="Enter country of origin" />
                                        @error('end_time')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label">Year</label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ old('year') == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ old('year') == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ old('year') == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ old('year') == 2022 ? 'selected' : '' }}>2022
                                            </option>
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="format" class="form-label">Format</label>
                                        <input type="text" class="form-control @error('format') is-invalid @enderror"
                                            id="format" name="format" value="{{ old('format') }}"
                                            placeholder="Format" />
                                        @error('format')
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
