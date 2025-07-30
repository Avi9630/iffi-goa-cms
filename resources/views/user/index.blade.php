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
        <a href={{ route('user.create') }} class="btn btn-primary btn-flat inline-block m-2">
            Add User
        </a>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Sr.Nom</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->getRoleNames()->isEmpty())
                                @can('assign-role')
                                    <a href="{{ url('assign_role', $user->id) }}" class="btn btn-sm btn-success">ASSIGN-ROLE</a>
                                @endcan
                            @else
                                {{ $user->getRoleNames()->implode('", "') }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-info btn-sm">Edit</a>
                            @can('delete')
                                @if ($role->name != Auth::user()->hasRole($role->name))
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
