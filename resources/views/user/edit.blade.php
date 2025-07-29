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
                                <div class="card-title">Role form</div>
                            </div>
                            <form action="{{ route('role.update',$role->id) }}" method="POST" >
                                @csrf @method('PUT')
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Name</label>
                                        <input type="text" name="role_name" class="form-control @error('role_name') is-invalid @enderror"
                                            value="{{ old('role_name', $role->name ?? '') }}" placeholder="Enter role name">
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
