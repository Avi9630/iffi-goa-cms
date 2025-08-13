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
        
        <a href={{ route('role.create') }} class="btn btn-primary btn-flat inline-block m-2">
            Add Role
        </a>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Sr.Nom</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('role.edit', $role->id) }}" class="btn btn-info btn-sm">Edit</a>
                            @if ($role->name != Auth::user()->hasRole($role->name))
                                <form action="{{ route('role.destroy', $role->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
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
