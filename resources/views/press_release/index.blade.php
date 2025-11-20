@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Press release</strong></h3>
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

    {{-- Search --}}
    <div class="app-content mt-2">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Search</div>
                        </div>
                        <form action="{{ route('pressRelease.search') }}" method="GET">
                            @csrf @method('GET')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="pr_category_id" class="form-label"><strong>Category</strong></label>
                                        <select name="pr_category_id" id="pr_category_id"
                                            class="form-select @error('pr_category_id') is-invalid @enderror">
                                            <option value="" selected>Select Category</option>
                                            <option value="1" {{ old('pr_category_id') == 1 ? 'selected' : '' }}>
                                                PRESS RELEASES BY PIB
                                            </option>
                                            <option value="2" {{ old('pr_category_id') == 2 ? 'selected' : '' }}>
                                                MEDIA COVERAGE (NON PIB)
                                            </option>
                                        </select>
                                        @error('pr_category_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label"><strong>Title</strong></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ isset($payload['title']) ? $payload['title'] : '' }}">
                                        @error('title')
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
                                <a href={{ route('press-release.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Press Release
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Title</th>
                                        <th>Publish Date</th>
                                        {{-- <th>Description</th> --}}
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pressReleases as $pressRelease)
                                        <tr class="align-middle">
                                            <td>{{ $pressRelease->id }}</td>
                                            <td>{{ $pressRelease->title }}</td>
                                            <td>{{ $pressRelease->publish_date }}</td>
                                            {{-- <td>{{ $pressRelease->description }}</td> --}}
                                            <td>
                                                <form action="{{ route('pressRelease.toggle', $pressRelease->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $pressRelease->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $pressRelease->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('press-release.edit', $pressRelease->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('press-release.destroy', $pressRelease->id) }}"
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
                                {{ $pressReleases->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
