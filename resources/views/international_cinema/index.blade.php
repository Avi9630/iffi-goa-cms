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
                                    Add International Cinema
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Curated Section</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Award</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($internationalCinemas as $internationalCinema)
                                        <tr class="align-middle">
                                            <td>{{ $internationalCinema->id }}</td>
                                            <td>{{ $internationalCinema->curatedSection->title  }}</td>
                                            <td>{{ $internationalCinema->title }}</td>
                                            <td>{{ $internationalCinema->slug }}</td>
                                            <td>{{ $internationalCinema->award }}</td>
                                            <td>
                                                <form action="{{ route('internationalCinema.toggle', $internationalCinema->id) }}"
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
                                                <a href="{{ route('international-cinema.edit', $internationalCinema->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('international-cinema.destroy', $internationalCinema->id) }}"
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
