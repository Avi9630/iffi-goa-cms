@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Jury Details</strong></h3>
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
                        <form action="{{ route('juryDetail.search') }}" method="GET">
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

                                            @foreach ($IPOfficialSelections as $key => $selection)
                                                <option name="official_selection_id" value="{{ $selection->id }}"
                                                    {{ isset($payload['official_selection_id']) && $payload['official_selection_id'] == $selection->id ? 'selected' : '' }}>
                                                    {{ $selection->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('official_selection_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label"><strong>Name</strong></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ isset($payload['name']) ? $payload['name'] : '' }}">
                                        @error('name')
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
                                <a href={{ route('jury-detail.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Jury
                                </a>
                                <a href={{ route('jury-detail.index') }} class="btn btn-sm btn-secondary btn-flat ">
                                    Reset
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Image</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($juryDetails as $juryDetail)
                                        <tr class="align-middle">
                                            <td>{{ $juryDetail->id }}</td>
                                            <td>{{ $juryDetail->official_selection_id == 1 ? 'Feature' : ($juryDetail->official_selection_id == 2 ? 'Non-Feature' : ($juryDetail->official_selection_id == 3 ? 'Chairperson' : '')) }}
                                            </td>
                                            <td>{{ $juryDetail->name }}</td>
                                            <td>{!! $juryDetail->position !!}</td>
                                            <td>
                                                @php
                                                    $path =
                                                        env('IMAGE_UPLOAD_BASE_URL') . env('JURY_IMAGE_DESTINATION');
                                                @endphp
                                                @if (!empty($juryDetail->img_src))
                                                    <img src="{{ $path . '/' . $juryDetail->img_src }}" alt="Current Image"
                                                        class="img-fluid mt-2" style="max-width: 100px;">
                                                @elseif(!empty($juryDetail->img_url))
                                                    <img src="{{ $juryDetail->img_url }}" alt="Current Image"
                                                        class="img-fluid mt-2" style="max-width: 100px;">
                                                @else
                                                <p>images not uploaded</p>
                                                @endif
                                            </td>
                                            <td>{{ $juryDetail->year }}</td>
                                            <td>
                                                <form action="{{ route('juryDetail.toggle', $juryDetail->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $juryDetail->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $juryDetail->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('jury-detail.edit', $juryDetail->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('jury-detail.destroy', $juryDetail->id) }}"
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
                        {{-- <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                {{ $juryDetails->withQueryString()->links() }}
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
