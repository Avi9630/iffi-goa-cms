@extends('layouts.app')
@section('content')
    <div class="card-body table-responsive p-0">
        <div class="app-content-header">
            <div class="container-fluid">
                <span>
                    <h4 class="alert-danger"><strong>Speaker</strong></h4>
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
        <a href={{ route('ticker.create') }} class="btn btn-primary btn-flat inline-block m-1">
            Add Ticker
        </a>
        <table class="table table-striped align-middle m-1">
            <thead>
                <tr>
                    <th>Sr.Nom</th>
                    <th>Content</th>
                    <th>Sort Nom</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickers as $ticker)
                    <tr id="ticker-row-{{ $ticker->id }}">
                        <td>{{ $ticker->id }}</td>
                        <td>{{ html_entity_decode(strip_tags($ticker->content)) }}</td>
                        <td>{{ $ticker->sort_num }}</td>
                        <td>
                            <form action="{{ route('ticker.toggle', $ticker->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn {{ $ticker->status === 1 ? 'btn-success' : 'btn-danger' }}">
                                    {{ $ticker->status === 1 ? 'Enabled' : 'Disabled' }}
                                </button>
                            </form>
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('ticker.edit', $ticker->id) }}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{ route('ticker.destroy', $ticker->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
