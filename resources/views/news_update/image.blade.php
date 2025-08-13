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

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <form action="{{ route('newsUpdate.popupImageUpload') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="col-md-6">
                        <input type="file" name="image" class="form-control mb-2" required>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-secondary">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 p-1">
                <a href={{ route('news-update.index') }} class="btn btn-sm btn-primary btn-flat">NewsUpdate</a>
            </div>
        </div>
    </div>
    <br>
    <!-- Gallery -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                @foreach ($images as $image)
                    <div class="col-lg-4 mb-4">
                        <img src="{{ $image['url'] }}"class="w-100 shadow-1-strong rounded mb-4"
                            alt="Mountains in the Clouds" height="100px" width="100px" />

                        <a href="{{ $image['url'] }}" target="_blank"><span>{{ $image['url'] }}</span></a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Gallery -->
@endsection
