@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Master Class Topic</strong></h3>
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
                                <a href="{{ route('master-class.index') }}" class="btn btn-secondary btn-sm">
                                    Details List</a>
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
                                <a href="{{ route('master-class-topic.create') }}" class="btn btn-primary btn-sm">
                                    Add Topic</a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Master Date</th>
                                        <th>topic</th>
                                        <th>description</th>
                                        <th>Master Details</th>
                                        <th>Speakers</th>
                                        <th>Moderator</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($masterClassesTpic as $masterTopic)
                                        <tr class="align-middle">
                                            <td>{{ $masterTopic->id }}</td>
                                            <td>{{ $masterTopic->masterDate->date }}</td>
                                            <td>{{ $masterTopic->title }}</td>
                                            <td>{{ $masterTopic->description }}</td>

                                            <td>
                                                <a href="{{ route('masterClass.addDetail', $masterTopic->id) }}"
                                                    class="btn btn-info btn-sm">ADD</a>
                                            </td>

                                            <td>
                                                <a href="{{ route('masterClass.addSpeaker', $masterTopic->id) }}"
                                                    class="btn btn-primary btn-sm">ADD</a>
                                            </td>

                                            <td>
                                                <a href="{{ route('masterClass.addModerator', $masterTopic->id) }}"
                                                    class="btn btn-secondary btn-sm">ADD</a>
                                            </td>

                                            <td>
                                                <form
                                                    action="{{ route('masterClassTopic.toggleStatus', $masterTopic->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $masterTopic->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $masterTopic->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>

                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('master-class-topic.edit', $masterTopic->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('master-class-topic.destroy', $masterTopic->id) }}"
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
