@extends('layouts.app')
@section('content')
    <main class="app-main">
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

                                <h3 class="card-title">Create ticker
                                    <a href={{ route('ticker.index') }} class="btn btn-warning btn-flat inline-block m-1">
                                        Reset
                                    </a>
                                </h3>

                            </div>
                            <form action="{{ route('ticker.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Content</label>
                                        <textarea name="content" id="content" cols="30" rows="10"
                                            class="form-control @error('content') is-invalid @enderror"></textarea>
                                        @error('content')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
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
    </main>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor.create(document.querySelector("#content"))
            .catch(error => {
                console.error(error);
            });
    });
</script>
