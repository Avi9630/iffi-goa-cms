@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">International Media</h3>
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
                                <a href={{ route('international-cinema.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Cinema
                                </a>
                                <a href={{ route('ic-basic-detail.index') }} class="btn btn-sm btn-info btn-flat">
                                    List International Cinema Basic Detail
                                </a>
                            </h3>
                            
                            {{-- Search --}}
                            <form action="{{ route('internationalCinema.search') }}">
                                @csrf
                                <div class="input-group input-group-sm float-end" style="width: 300px;">
                                    <select name="search" class="form-select">
                                        <option value="" selected>Select Year</option>
                                        <option value="2025" {{ old('year') == 2025 ? 'selected' : '' }}>2025
                                        </option>
                                        <option value="2024" {{ old('year') == 2024 ? 'selected' : '' }}>2024
                                        </option>
                                        <option value="2023" {{ old('year') == 2023 ? 'selected' : '' }}>2023
                                        </option>
                                    </select>
                                    <div class="input-group-append" style="margin-left: 2px">
                                        <button type="submit" class="btn btn-dark btn-sm btn-flat">Search</button>
                                    </div>
                                </div>
                            </form>
                            
                            {{-- Upload CSV --}}
                            <form action="{{ route('internationalCinema.uploadCSV') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group input-group-sm float-end" style="width: 300px;">
                                    <input type="file" name="file"
                                        class="form-select @error('file') is-invalid @enderror" required>
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                    <div class="input-group-append" style="margin-left: 2px">
                                        <button type="submit" class="btn btn-dark btn-sm btn-flat">Upload CSV</button>
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
                                        <th>Slug</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($internationalCinemas as $internationalCinema)
                                        <tr class="align-middle">
                                            <td>{{ $internationalCinema->id }}</td>
                                            <td>{{ $internationalCinema->curatedSection->title }}</td>
                                            <td>{{ $internationalCinema->title }}</td>
                                            <td>{{ $internationalCinema->slug }}</td>
                                            <td>
                                                <form
                                                    action="{{ route('internationalCinema.toggle', $internationalCinema->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $internationalCinema->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $internationalCinema->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('internationalCinema.addBasicDetail', $internationalCinema->id) }}"
                                                    class="btn btn-secondary btn-sm">Add Basic Details</a>

                                                <a href="{{ route('international-cinema.edit', $internationalCinema->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form
                                                        action="{{ route('international-cinema.destroy', $internationalCinema->id) }}"
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
                                {{ $internationalCinemas->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
