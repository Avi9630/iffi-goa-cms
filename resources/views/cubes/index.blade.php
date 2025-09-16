@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Cube</h3>
                </div>
                <div class="col-sm-6">
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
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href={{ route('cube.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Image</th>
                                        <th>Link</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cubes as $cube)
                                        <tr class="align-middle">
                                            <td>{{ $cube->id }}</td>
                                            <td>
                                                @php
                                                    $location = env('IMAGE_UPLOAD_BASE_URL') . env('CUBE_DESTINATION');
                                                @endphp
                                                @if (!empty($cube->image_url))
                                                    <img src="{{ $cube->image_url }}" alt="" height="50px"
                                                        width="100px">
                                                @else
                                                    <img src="{{ $location . '/' . $cube->image_name }}" alt=""
                                                        height="50px" width="100px">
                                                @endif
                                            </td>
                                            <td>{{ $cube->link }}</td>
                                            <td>
                                                <form action="{{ route('cube.toggleStatus', $cube->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $cube->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $cube->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('cube.edit', $cube->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('cube.destroy', $cube->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
