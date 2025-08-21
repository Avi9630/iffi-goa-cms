@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">News & Update</h3>
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
                                <a href={{ route('news-update.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add NewsUpdate
                                </a>
                                <a href={{ route('newsUpdate.popupImage') }} class="btn btn-sm btn-info btn-flat ">
                                    PopupImage
                                </a>
                                <a href={{ route('news-update.index') }} class="btn btn-sm btn-secondary btn-flat ">
                                    Reset
                                </a>
                            </h3>
                            <form action="{{ route('newsUpdate.search') }}">
                               @csrf
                                <div class="input-group input-group-sm float-end" style="width: 300px;">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by title or description" value="{{ request('search') }}">
                                    <div class="input-group-append" style="margin-left: 2px">
                                        <button type="submit" class="btn btn-info btn-sm btn-flat">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th style="white-space: nowrap">Have popup</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($newsUpdates as $newsUpdate)
                                        <tr class="align-middle">
                                            <td>{{ $newsUpdate->id }}</td>
                                            <td>{{ $newsUpdate->title }}</td>
                                            <td>{{ $newsUpdate->description }}</td>
                                            <td>
                                                <form action="{{ route('newsUpdate.toggle', $newsUpdate->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $newsUpdate->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $newsUpdate->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                @if ($newsUpdate->have_popup == 1)
                                                    <a href="{{ route('newsUpdate.popupToggle', $newsUpdate->id) }}"
                                                        class="btn btn-primary btn-sm btn-flat">Active</a>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>Deactive</button>
                                                @endif
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('news-update.edit', $newsUpdate->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('news-update.destroy', $newsUpdate->id) }}"
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
                                {{ $newsUpdates->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
