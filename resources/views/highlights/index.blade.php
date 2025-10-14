@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><strong>Highlights</strong></h3>
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
                                <a href={{ route('highlight.create') }} class="btn btn-sm btn-primary btn-flat">
                                    Add Highlight
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.Nom</th>
                                        <th>Photo</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($highlights as $highlight)
                                        <tr class="align-middle">
                                            <td>{{ $highlight->id }}</td>
                                            <td>
                                                @php
                                                    $location = env('IMAGE_UPLOAD_BASE_URL') . env('HIGHLIGHT_DESTINATION');
                                                @endphp

                                                @if (!empty($highlight->img_url))
                                                    <img src="{{ $highlight->img_url }}" alt="" height="50px"
                                                        width="100px">
                                                @else
                                                    <img src="{{ $location . '/' . $highlight->img_src }}" alt=""
                                                        height="50px" width="100px">
                                                @endif
                                            </td>

                                            <td>
                                                <form action="{{ route('highlight.toggle', $highlight->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="btn {{ $highlight->status === 1 ? 'btn-success' : 'btn-danger' }} btn-sm">
                                                        {{ $highlight->status === 1 ? 'Enabled' : 'Disabled' }}
                                                    </button>
                                                </form>
                                            </td>
                                            
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('highlight.edit', $highlight->id) }}"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                @can('delete')
                                                    <form action="{{ route('highlight.destroy', $highlight->id) }}"
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
                                {{ $highlights->withQueryString()->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
