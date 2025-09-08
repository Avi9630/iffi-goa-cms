@extends('layouts.app')
@section('content')
    <div class="card-body table-responsive p-0">
        <div class="app-content-header">
            <div class="container-fluid">
                <span>
                    <h4 class="alert-danger"><strong>Permission</strong></h4>
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
        <a href={{ route('permission.create') }} class="btn btn-primary btn-flat inline-block m-1">
            Add Permission
        </a>
        <table class="table table-striped align-middle m-1">
            <thead>
                <tr>
                    <th>Sr.Nom</th>
                    <th>Permission Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr id="ticker-row-{{ $permission->id }}">
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('permission.destroy', $permission->id) }}" method="POST"
                                style="display:inline;"> @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
