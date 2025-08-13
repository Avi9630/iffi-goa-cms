@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 d-flex" style="justify-content: space-between;">
                    <h3 class="mb-0">International Cinema Basic Details</h3>
                    <a href="{{ route('ic-basic-detail.edit', $icBasicDetail->id) }}"
                        class="btn btn-info float-right">Edit</a>
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
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $fields = [
                                        'Director' => 'director',
                                        'Producer' => 'producer',
                                        'Screenplay' => 'screenplay',
                                        'Cinematographer' => 'cinematographer',
                                        'Editor' => 'editor',
                                        'Cast' => 'cast',
                                        'DOP' => 'dop',
                                        'Other Details' => 'other_details',
                                        'Synopsis' => 'synopsis',
                                        'Director Bio' => 'director_bio',
                                        'Producer Bio' => 'producer_bio',
                                        'Sales Agent' => 'sales_agent',
                                        'Award' => 'award',
                                        'Writer' => 'writer',
                                        'Trailer Link' => 'trailer_link',
                                        'Official Selection' => 'official_selection',
                                        'Best Film Award' => 'best_film_award',
                                        'Director and Producer' => 'director_and_producer',
                                        'Original Title' => 'original_title',
                                        'Co Produced' => 'co_produced',
                                        'Festivals' => 'festivals',
                                        'Drama' => 'drama',
                                        'History' => 'history',
                                        'Nomination' => 'nomination',
                                    ];
                                @endphp

                                @foreach ($fields as $label => $field)
                                    <div class="col-md-4 mb-1">
                                        <div class="border rounded p-2 bg-light">
                                            <strong>{{ $label }}:</strong>
                                            {{ $icBasicDetail->$field ?? '-' }}
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
