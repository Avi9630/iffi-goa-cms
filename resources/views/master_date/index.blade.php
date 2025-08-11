@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Master Class Dates</h3>
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
                        {{-- <div class="card-header">
                            <h3 class="card-title">
                                <a href={{ route('master-class-topic.index') }} class="btn btn-sm btn-primary btn-flat">
                                    Master Class Topic
                                </a>
                            </h3>
                        </div> --}}
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Date</th>
                                        <th>Topic</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($masterDates as $date)
                                        <tr class="align-middle">
                                            <td>{{ $date->id }}</td>
                                            <td>{{ $date->date }}</td>
                                            <td>
                                                <a href={{ route('masterClassTopic.addTopic',$date->id) }}
                                                    class="btn btn-sm btn-primary btn-flat">
                                                    Add Topic
                                                </a>
                                            </td>
                                            {{-- <td>
                                                <form action="{{ route('masterClass.toggleStatus', $master->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $master->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $master->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td> --}}

                                            {{-- <td style="white-space: nowrap;">
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
                                            </td> --}}
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
