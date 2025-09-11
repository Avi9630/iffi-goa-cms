@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Speaker</strong></h3>
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
                                <a href={{ route('speaker.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Speaker
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
                                        <th>Speaker Name</th>
                                        <th>Speaker Details</th>
                                        <th>Image</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($speakers as $speaker)
                                        <tr class="align-middle">
                                            <td>{{ $speaker->id }}</td>
                                            <td>{{ $speaker->masterTopic->masterDate->date }}</td>
                                            <td>{{ $speaker->masterTopic->title }}</td>
                                            <td>{{ $speaker->speaker_name }}</td>
                                            <td>
                                                <div style="max-height: 100px; overflow-y: auto; white-space: pre-wrap;">
                                                    {!! $speaker->speaker_detail !!}
                                                </div>
                                            </td>
                                            <td>
                                                <img src="{{ $speaker->image_url }}" alt="" height="50px"
                                                    width="100px">
                                                {{ $speaker->image_name }}
                                            </td>

                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('speaker.edit', $speaker->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('speaker.destroy', $speaker->id) }}" method="POST"
                                                        style="display:inline;"
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
