@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"> Indian Panorama</h3>
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
                            <div class="card-title">Search
                                <a href={{ route('indian-panorama.index') }} class="btn btn-sm btn-primary btn-flat">
                                    Reset
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('indianPanorama.search') }}" method="GET">
                            @csrf @method('GET')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="official_selection_id" class="form-label">
                                            <strong>Official Selection</strong>
                                        </label>
                                        <select name="official_selection_id" id="official_selection_id"
                                            class="form-select @error('official_selection_id') is-invalid @enderror">
                                            <option value="" selected>Select Official Selection</option>

                                            @foreach ($IPOfficialSelection as $key => $selection)
                                                <option name="official_selection_id" value="{{ $key+1 }}"
                                                    {{ isset($payload['official_selection_id']) && $payload['official_selection_id'] == $key ? 'selected' : '' }}>
                                                    {{ $selection->title }}
                                                </option>
                                            @endforeach

                                        </select>
                                        @error('official_selection_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ isset($payload['year']) && $payload['year'] == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ isset($payload['year']) && $payload['year'] == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ isset($payload['year']) && $payload['year'] == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ isset($payload['year']) && $payload['year'] == 2022 ? 'selected' : '' }}>2022
                                            </option>
                                        </select>
                                        @error('year')
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
                                <a href={{ route('indian-panorama.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Cinema
                                </a>
                            </h3>

                            {{-- Upload CSV --}}
                            <form action="{{ route('indianPanorama.uploadCSV') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group input-group-sm float-end" style="width: 500px;">
                                    <input type="file" name="file"
                                        class="form-select @error('file') is-invalid @enderror" required>
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                    <div class="input-group-append" style="margin-left: 2px">
                                        <button type="submit" class="btn btn-dark  btn-flat">Upload CSV</button>
                                    </div>
                                </div>
                            </form>

                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cinema ID</th>
                                        <th>Curated Section</th>
                                        <th>Title</th>
                                        <th>Directed By</th>
                                        <th>Language</th>
                                        <th>Year</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($indianPanoramas as $indianPanorama)
                                        <tr class="align-middle">
                                            <td>{{ $indianPanorama->id }}</td>
                                            <td>{{ $indianPanorama->ipOfficialSelection->title }}</td>
                                            <td>{{ $indianPanorama->title }}</td>
                                            <td>{{ $indianPanorama->directed_by }}</td>
                                            <td>{{ $indianPanorama->language }}</td>
                                            <td>{{ $indianPanorama->year }}</td>
                                            <td>
                                                <form action="{{ route('indianPanorama.toggle', $indianPanorama->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $indianPanorama->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $indianPanorama->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('indian-panorama.edit', $indianPanorama->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('indian-panorama.destroy', $indianPanorama->id) }}"
                                                        method="POST" style="display:inline;">
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
                                {{ $indianPanoramas->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
