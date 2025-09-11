@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Moderator</strong></h3>
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

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('master-class-date.index') }}" class="btn btn-primary btn-sm">
                                    Date List</a>
                                <a href="{{ route('master-class-topic.index') }}" class="btn btn-secondary btn-sm">
                                    Topic List</a>
                                <a href="{{ route('master-class.index') }}" class="btn btn-success btn-sm">Details</a>
                                <a href="{{ route('speaker.index') }}" class="btn btn-info btn-sm">Speaker List</a>
                            </h3>
                        </div>
                    </div>
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
                                <a href={{ route('moderator.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Moderator
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Master Date</th>
                                        <th>Topic ID</th>
                                        <th>Moderator Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($moderators as $moderator)
                                    {{-- @php
                                        dd($moderator->masterTopic->masterDate);
                                    @endphp --}}
                                        <tr class="align-middle">
                                            <td>{{ $moderator->id }}</td>
                                            <td>{{ $moderator->masterTopic->masterDate->date }}</td>
                                            <td>{{ $moderator->masterTopic->title }}</td>
                                            <td>{{ $moderator->moderator_name }}</td>
                                            <td>
                                                <form action="{{ route('moderator.toggleStatus', $moderator->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $moderator->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $moderator->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('moderator.edit', $moderator->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('moderator.destroy', $moderator->id) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this record? This action cannot be undone.');">
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
                </div>
            </div>
        </div>
    </div>
@endsection
