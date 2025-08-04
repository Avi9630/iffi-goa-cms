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
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr.Nom</th>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Image Name</th>
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
                                                <td>{{ $peacock->image_url }}</td>
                                                <td>{{ $peacock->image_name }}</td>
                                                <td>{{ $peacock->poster_url }}</td>
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
