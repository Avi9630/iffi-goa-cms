@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Master Class Details</strong></h3>
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
                                <a href="{{ route('speaker.index') }}" class="btn btn-success btn-sm">Speaker List</a>
                                <a href="{{ route('moderator.index') }}" class="btn btn-info btn-sm">Moderator List</a>
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
                                <a href={{ route('master-class.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Details
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
                                        <th>start_time</th>
                                        <th>end_time</th>
                                        <th>Format</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($masterClasses as $master)
                                    {{-- @php
                                        dd($master->masterTopic->masterDate->date);
                                    @endphp --}}
                                        <tr class="align-middle">
                                            <td>{{ $master->id }}</td>
                                            <td>{{ $master->masterTopic->masterDate->date }}</td>
                                            <td>{{$master->masterTopic->title }}</td>
                                            <td>{{ $master->start_time }}</td>
                                            <td>{{ $master->end_time }}</td>
                                            <td>{{ $master->format }}</td>

                                            <td>
                                                <form action="{{ route('masterClass.toggleStatus', $master->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $master->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $master->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>

                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('master-class.edit', $master->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('master-class.destroy', $master->id) }}"
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
                        {{-- <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                {{ $internationalCinemas->withQueryString()->links() }}
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
