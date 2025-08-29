@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">International Cinema Basic Details</h3>
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
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href={{ route('international-cinema.index') }} class="btn btn-sm btn-primary btn-flat">
                                    International Cinema
                                </a>
                            </h3>
                            {{-- Search --}}
                            <form action="{{ route('icBasicDetail.search') }}">
                                @csrf
                                <div class="input-group input-group-sm float-end" style="width: 300px;">
                                    <input type="text" name="search" id="search" class="form-control">
                                    <div class="input-group-append" style="margin-left: 2px">
                                        <button type="submit" class="btn btn-info btn-sm btn-flat">Search</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Basic Details ID</th>
                                        <th>Cinema ID</th>
                                        <th>Director</th>
                                        <th>Producer</th>
                                        <th>Screenplay</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($icBasicDetails as $icBasicDetail)
                                        <tr class="align-middle">
                                            <td>{{ $icBasicDetail->id }}</td>
                                            <td>{{ $icBasicDetail->cinema_id }}</td>
                                            <td>{{ $icBasicDetail->director }}</td>
                                            <td>{{ $icBasicDetail->producer }}</td>
                                            <td>{{ $icBasicDetail->screenplay }}</td>
                                            <td>
                                                <form action="{{ route('icBasicDetail.toggle', $icBasicDetail->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $icBasicDetail->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $icBasicDetail->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                {{-- <a href="{{ route('ic-basic-detail.show', $icBasicDetail->id) }}"
                                                    class="btn btn-primary btn-sm">View</a> --}}

                                                <a href="{{ route('ic-basic-detail.edit', $icBasicDetail->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                
                                                @can('delete')
                                                    <form action="{{ route('ic-basic-detail.destroy', $icBasicDetail->id) }}"
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
                                @isset($icBasicDetails)
                                    {{ $icBasicDetails->withQueryString()->links() }}
                                @endisset
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
