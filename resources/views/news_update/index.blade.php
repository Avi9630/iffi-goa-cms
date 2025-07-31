@extends('layouts.app')
@section('content')
    <div class="card-body table-responsive p-0">
        <div class="app-content-header">
            <div class="container-fluid">
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
        <a href={{ route('news-update.create') }} class="btn btn-primary btn-flat inline-block m-1">
            Add NewsUpdate
        </a>
        <table class="table table-striped align-middle m-1">
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
                    <tr>
                        <td>{{ $newsUpdate->id }}</td>
                        <td>{{ $newsUpdate->title }}</td>
                        <td>{{ $newsUpdate->description }}</td>
                        <td>
                            <form action="{{ route('newsUpdate.toggle', $newsUpdate->id) }}" method="POST"
                                style="display:inline;">
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
                                    class="btn btn-info btn-sm">Active</a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Deactive</button>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('news-update.edit', $newsUpdate->id) }}" class="btn btn-info btn-sm">Edit</a>
                            @can('delete')
                                <form action="{{ route('news-update.destroy', $newsUpdate->id) }}" method="POST"
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
@endsection
