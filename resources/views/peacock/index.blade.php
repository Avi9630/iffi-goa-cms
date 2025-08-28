@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Peacock</h3>
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
                                <a href={{ route('peacock.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Peacock
                                </a>
                                <a href={{ route('peacock.index') }} class="btn btn-sm btn-info btn-flat">
                                    Reset
                                </a>
                            </h3>
                             <form action="{{ route('peacock.search') }}">
                                @csrf
                                <div class="input-group input-group-sm float-end" style="width: 300px;">
                                    <select name="search" class="form-select">
                                        <option value="" selected>Select Year</option>
                                        <option value="2025" {{ isset($year) && $year == 2025 ? 'selected' : '' }}>2025</option>
                                            <option value="2024" {{ isset($year) && $year == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ isset($year) && $year == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ isset($year) && $year == 2022 ? 'selected' : '' }}>2022
                                            </option>
                                            <option value="2021" {{ isset($year) && $year == 2021 ? 'selected' : '' }}>2021
                                            </option>
                                            <option value="2020" {{ isset($year) && $year == 2020 ? 'selected' : '' }}>2020
                                            </option>
                                            <option value="2019" {{ isset($year) && $year == 2019 ? 'selected' : '' }}>2019
                                            </option>
                                            <option value="2018" {{ isset($year) && $year == 2018 ? 'selected' : '' }}>2018
                                            </option>
                                            <option value="2017" {{ isset($year) && $year == 2017 ? 'selected' : '' }}>2017
                                            </option>
                                    </select>
                                    <div class="input-group-append" style="margin-left: 2px">
                                        <button type="submit" class="btn btn-info btn-sm btn-flat">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.Nom</th>
                                            <th>Title</th>
                                            <th>PDF</th>
                                            <th>Poster</th>
                                            <th>status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($peacocks as $peacock)
                                            <tr class="align-middle">
                                                <td>{{ $peacock->id }}</td>
                                                <td>{{ $peacock->title }}</td>
                                                <td>
                                                    @if ($peacock->image_url)
                                                        <iframe src="{{ $peacock->image_url }}" width="100%"
                                                            height="100px"></iframe>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($peacock->poster_url)
                                                        <img src="{{ $peacock->poster_url }}" alt="Current Image"
                                                            class="img-fluid mt-2" style="max-width: 100px;">
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('peacock.toggle', $peacock->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="btn {{ $peacock->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                            {{ $peacock->status === 1 ? 'Enabled' : 'Disabled' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td style="white-space: nowrap;">
                                                    <a href="{{ route('peacock.edit', $peacock->id) }}"
                                                        class="btn btn-info btn-sm">Edit</a>
                                                    @can('delete')
                                                        <form action="{{ route('peacock.destroy', $peacock->id) }}"
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
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                {{ $peacocks->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
