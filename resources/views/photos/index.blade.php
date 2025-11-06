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

    <div class="app-content mt-2">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Search</div>
                        </div>
                        <form action="{{ route('photo.fullSearch') }}" method="GET">
                            @csrf @method('GET')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="photo_category_id" class="form-label">
                                            <strong>Photo category</strong>
                                        </label>
                                        <select name="photo_category_id" id="photo_category_id"
                                            class="form-select @error('photo_category_id') is-invalid @enderror">
                                            <option value="" selected>Select photo category</option>

                                            @foreach ($photoCategories as $key => $categories)
                                                <option name="photo_category_id" value="{{ $categories->id }}"
                                                    {{ isset($payload['photo_category_id']) && $payload['photo_category_id'] == $categories->id ? 'selected' : '' }}>
                                                    {{ $categories->category }}
                                                </option>
                                            @endforeach

                                        </select>
                                        @error('photo_category_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025"
                                                {{ isset($payload['year']) && $payload['year'] == 2025 ? 'selected' : '' }}>
                                                2025
                                            </option>
                                            <option value="2024"
                                                {{ isset($payload['year']) && $payload['year'] == 2024 ? 'selected' : '' }}>
                                                2024
                                            </option>
                                            <option value="2023"
                                                {{ isset($payload['year']) && $payload['year'] == 2023 ? 'selected' : '' }}>
                                                2023
                                            </option>
                                            <option value="2022"
                                                {{ isset($payload['year']) && $payload['year'] == 2022 ? 'selected' : '' }}>
                                                2022
                                            </option>
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="photo_category_id" class="form-label">
                                            <strong>Highlights</strong>
                                        </label>
                                        <select name="highlights" id="highlights"
                                            class="form-select @error('highlights') is-invalid @enderror">
                                            <option value="" selected>Select highlights</option>
                                            <option value="1"
                                                {{ isset($payload['highlights']) && $payload['highlights'] == 1 ? 'selected' : '' }}>
                                                Yes
                                            </option>
                                            <option value="0"
                                                {{ isset($payload['highlights']) && $payload['highlights'] == 0 ? 'selected' : '' }}>
                                                No
                                            </option>
                                        </select>
                                        @error('highlights')
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
                                <a href={{ route('photo.index') }} class="btn btn-sm btn-warning btn-flat">
                                    Reset
                                </a>
                            </h3>
                            {{-- <form action="{{ route('photo.search') }}">
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
                            </form> --}}
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
                        {{-- card-footer clearfix --}}
                        <div class="text-center card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                {{ $photos->withQueryString()->links() }}
                            </ul>
                        </div>
                        <br><br>
                        <h3 class="text-center"><strong>Videos</strong></h3>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
