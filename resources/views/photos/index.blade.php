@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Photos</strong></h3>
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
                                <a href={{ route('photo.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Photos
                                </a>
                                <a href={{ route('photo.index') }} class="btn btn-sm btn-info btn-flat">
                                    Reset
                                </a>
                            </h3>
                            <form action="{{ route('photo.search') }}">
                                @csrf
                                <div class="input-group input-group-sm float-end" style="width: 300px;">
                                    <select name="search" class="form-select">
                                        <option value="" selected>Select Year</option>
                                        <option value="2025" {{ old('year') == 2025 ? 'selected' : '' }}>2025
                                        </option>
                                        <option value="2024" {{ old('year') == 2024 ? 'selected' : '' }}>2024
                                        </option>
                                    </select>
                                    <div class="input-group-append" style="margin-left: 2px">
                                        <button type="submit" class="btn btn-info btn-sm btn-flat">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <h3 class="text-center"><strong>Images</strong></h3>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Category</th>
                                        <th>Image</th>
                                        <th>Year</th>
                                        <th>Active</th>
                                        <th>Status</th>
                                        <th>Highlights</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($photos as $photo)
                                        <tr class="align-middle">
                                            <td>{{ $photo->id }}</td>
                                            <td>{{ $photo->category->category ?? '' }}</td>
                                            <td>
                                                @empty(!$photo->img_url)
                                                    <img src="{{ $photo->img_url }}" alt="{{ $photo->img_caption }}"
                                                        class="img-thumbnail" width="100">
                                                @endempty
                                            </td>
                                            <td>{{ $photo->year }}</td>
                                            <td>
                                                <form action="{{ route('photo.activeToggle', $photo->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $photo->is_active === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $photo->is_active === 1 ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('photo.toggle', $photo->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $photo->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $photo->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{ route('photo.highlightToggle', $photo->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $photo->highlights === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $photo->highlights === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('photo.edit', $photo->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('photo.destroy', $photo->id) }}" method="POST"
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
                        <h3 class="text-center"><strong>Vides</strong></h3>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Caption</th>
                                        <th>Video URL</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($videos as $video)
                                        <tr class="align-middle">
                                            <td>{{ $video->id }}</td>
                                            <td>
                                                @empty(!$video->img_caption)
                                                    {{ $video->img_caption ?? '' }}
                                                @endempty
                                            </td>
                                            <td>{{ $video->video_url }}</td>
                                            <td>{{ $video->year }}</td>
                                            <td>
                                                <form action="{{ route('photo.toggle', $video->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $video->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $video->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('photo.edit', $video->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('photo.destroy', $video->id) }}" method="POST"
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
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                {{ $photos->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
