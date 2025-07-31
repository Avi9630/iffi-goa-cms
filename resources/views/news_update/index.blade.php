@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">News & Update</h3>
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
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>title</th>
                                        <th>description</th>
                                        <th>status</th>
                                        <th>have_popup</th>
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
                                            <td>
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
