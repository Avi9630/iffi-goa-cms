@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">News & Update</h3>
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
                                <a href={{ route('latest-update.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add LatestUpdate
                                </a>
                                {{-- <a href={{ route('latestUpdate.popupImage') }} class="btn btn-sm btn-info btn-flat ">
                                    PopupImage
                                </a> --}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th style="white-space: nowrap">Button Content</th>
                                        <th>Title</th>
                                        <th>Link</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestUpdates as $latestUpdate)
                                        <tr class="align-middle">
                                            <td>{{ $latestUpdate->id }}</td>
                                            <td>{{ $latestUpdate->content }}</td>
                                            <td>{{ $latestUpdate->title }}</td>
                                            <td><a href="{{ $latestUpdate->link }}">{{ $latestUpdate->link }}</a></td>
                                            <td>
                                                <form action="{{ route('latestUpdate.toggle', $latestUpdate->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $latestUpdate->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $latestUpdate->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('latest-update.edit', $latestUpdate->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('latest-update.destroy', $latestUpdate->id) }}"
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
                                {{ $latestUpdates->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
