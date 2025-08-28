@extends('layouts.app')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
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
    <div class="app-content mt-2">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">International Cinema Basic Details Form</div>
                        </div>
                        <form action="{{ route('ic-basic-detail.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="cinema_id" class="form-label">Cinema ID</label>
                                        <input type="number" class="form-control @error('cinema_id') is-invalid @enderror"
                                            id="cinema_id" name="cinema_id" value="{{ $internationalCinema->id }}"
                                            readonly />
                                        @error('cinema_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Director --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="director" class="form-label">Director</label>
                                        <input type="text" class="form-control @error('director') is-invalid @enderror"
                                            id="director" name="director" value="{{ old('director') }}"
                                            placeholder="Enter director." />
                                        @error('director')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Producer --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="producer" class="form-label">Producer</label>
                                        <input type="text" class="form-control @error('producer') is-invalid @enderror"
                                            id="producer" name="producer" value="{{ old('producer') }}"
                                            placeholder="Enter producer." />
                                        @error('producer')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Screenplay --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="screenplay" class="form-label">Screenplay</label>
                                        <input type="text" class="form-control @error('screenplay') is-invalid @enderror"
                                            id="screenplay" name="screenplay" value="{{ old('screenplay') }}"
                                            placeholder="Enter screenplay." />
                                        @error('screenplay')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Cinematographer --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="cinematographer" class="form-label">Cinematographer</label>
                                        <input type="text"
                                            class="form-control @error('cinematographer') is-invalid @enderror"
                                            id="cinematographer" name="cinematographer"
                                            value="{{ old('cinematographer') }}" placeholder="Enter cinematographer." />
                                        @error('cinematographer')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="editor" class="form-label">Editor</label>
                                        <input type="text" class="form-control @error('editor') is-invalid @enderror"
                                            id="editor" name="editor" value="{{ old('editor') }}"
                                            placeholder="Enter editor." />
                                        @error('editor')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="cast" class="form-label">Cast</label>
                                        <input type="text" class="form-control @error('cast') is-invalid @enderror"
                                            id="cast" name="cast" value="{{ old('cast') }}"
                                            placeholder="Enter cast." />
                                        @error('cast')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="dop" class="form-label">DOP</label>
                                        <input type="text" class="form-control @error('dop') is-invalid @enderror"
                                            id="dop" name="dop" value="{{ old('dop') }}"
                                            placeholder="Enter DOP." />
                                        @error('dop')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="other_details" class="form-label">Other Details</label>
                                        <input type="text"
                                            class="form-control @error('other_details') is-invalid @enderror"
                                            id="other_details" name="other_details" value="{{ old('other_details') }}"
                                            placeholder="Enter other details." />
                                        @error('other_details')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="synopsis" class="form-label">Synopsis</label>
                                        <input type="text" class="form-control @error('synopsis') is-invalid @enderror"
                                            id="synopsis" name="synopsis" value="{{ old('synopsis') }}"
                                            placeholder="Enter synopsis." />
                                        @error('synopsis')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="director_bio" class="form-label">Director Bio</label>
                                        <input type="text"
                                            class="form-control @error('director_bio') is-invalid @enderror"
                                            id="director_bio" name="director_bio" value="{{ old('director_bio') }}"
                                            placeholder="Enter director bio." />
                                        @error('director_bio')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="producer_bio" class="form-label">Producer Bio</label>
                                        <input type="text"
                                            class="form-control @error('producer_bio') is-invalid @enderror"
                                            id="producer_bio" name="producer_bio" value="{{ old('producer_bio') }}"
                                            placeholder="Enter producer bio." />
                                        @error('producer_bio')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sales_agent" class="form-label">Sales Agent</label>
                                        <input type="text"
                                            class="form-control @error('sales_agent') is-invalid @enderror"
                                            id="sales_agent" name="sales_agent" value="{{ old('sales_agent') }}"
                                            placeholder="Enter sales agent." />
                                        @error('sales_agent')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="award" class="form-label">Award</label>
                                        <input type="text" class="form-control @error('award') is-invalid @enderror"
                                            id="award" name="award" value="{{ old('award') }}"
                                            placeholder="Enter award." />
                                        @error('award')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="writer" class="form-label">Writer</label>
                                        <input type="text" class="form-control @error('writer') is-invalid @enderror"
                                            id="writer" name="writer" value="{{ old('writer') }}"
                                            placeholder="Enter writer." />
                                        @error('writer')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="trailer_link" class="form-label">Trailer Link</label>
                                        <input type="text"
                                            class="form-control @error('trailer_link') is-invalid @enderror"
                                            id="trailer_link" name="trailer_link" value="{{ old('trailer_link') }}"
                                            placeholder="Enter trailer link." />
                                        @error('trailer_link')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="official_selection" class="form-label">Official Selection</label>
                                        <input type="text"
                                            class="form-control @error('official_selection') is-invalid @enderror"
                                            id="official_selection" name="official_selection"
                                            value="{{ old('official_selection') }}"
                                            placeholder="Enter official selection." />
                                        @error('official_selection')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="best_film_award" class="form-label">Best Film Award</label>
                                        <input type="text"
                                            class="form-control @error('best_film_award') is-invalid @enderror"
                                            id="best_film_award" name="best_film_award"
                                            value="{{ old('best_film_award') }}" placeholder="Enter best film award." />
                                        @error('best_film_award')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="director_and_producer" class="form-label">Director & Producer</label>
                                        <input type="text"
                                            class="form-control @error('director_and_producer') is-invalid @enderror"
                                            id="director_and_producer" name="director_and_producer"
                                            value="{{ old('director_and_producer') }}"
                                            placeholder="Enter director and producer." />
                                        @error('director_and_producer')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="original_title" class="form-label">Original Title</label>
                                        <input type="text"
                                            class="form-control @error('original_title') is-invalid @enderror"
                                            id="original_title" name="original_title"
                                            value="{{ old('original_title') }}" placeholder="Enter original title." />
                                        @error('original_title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="co_produced" class="form-label">Co-Produced</label>
                                        <input type="text"
                                            class="form-control @error('co_produced') is-invalid @enderror"
                                            id="co_produced" name="co_produced" value="{{ old('co_produced') }}"
                                            placeholder="Enter co-produced details." />
                                        @error('co_produced')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="festivals" class="form-label">Festivals</label>
                                        <input type="text"
                                            class="form-control @error('festivals') is-invalid @enderror" id="festivals"
                                            name="festivals" value="{{ old('festivals') }}"
                                            placeholder="Enter festivals." />
                                        @error('festivals')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="drama" class="form-label">Drama</label>
                                        <input type="text" class="form-control @error('drama') is-invalid @enderror"
                                            id="drama" name="drama" value="{{ old('drama') }}"
                                            placeholder="Enter drama." />
                                        @error('drama')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="history" class="form-label">History</label>
                                        <input type="text" class="form-control @error('history') is-invalid @enderror"
                                            id="history" name="history" value="{{ old('history') }}"
                                            placeholder="Enter history." />
                                        @error('history')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nomination" class="form-label">Nomination</label>
                                        <input type="text"
                                            class="form-control @error('nomination') is-invalid @enderror"
                                            id="nomination" name="nomination" value="{{ old('nomination') }}"
                                            placeholder="Enter nomination." />
                                        @error('nomination')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="premiere" class="form-label">Premiere</label>
                                        <input type="text"
                                            class="form-control @error('premiere') is-invalid @enderror"
                                            id="premiere" name="premiere" value="{{ old('premiere') }}"
                                            placeholder="Enter premiere." />
                                        @error('premiere')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="festival_history" class="form-label">Festival History</label>
                                        <input type="text"
                                            class="form-control @error('festival_history') is-invalid @enderror"
                                            id="festival_history" name="festival_history" value="{{ old('festival_history') }}"
                                            placeholder="Enter festival_history." />
                                        @error('festival_history')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="link_trailer" class="form-label">Link Trailer</label>
                                        <input type="text"
                                            class="form-control @error('link_trailer') is-invalid @enderror"
                                            id="link_trailer" name="link_trailer" value="{{ old('link_trailer') }}"
                                            placeholder="Enter link_trailer." />
                                        @error('link_trailer')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tags" class="form-label">Tags</label>
                                        <input type="text"
                                            class="form-control @error('tags') is-invalid @enderror"
                                            id="tags" name="tags" value="{{ old('tags') }}"
                                            placeholder="Enter tags." />
                                        @error('tags')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sales" class="form-label">Sales</label>
                                        <input type="text"
                                            class="form-control @error('sales') is-invalid @enderror"
                                            id="sales" name="sales" value="{{ old('sales') }}"
                                            placeholder="Enter sales." />
                                        @error('sales')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="instagram" class="form-label">Instagram</label>
                                        <input type="text"
                                            class="form-control @error('instagram') is-invalid @enderror"
                                            id="instagram" name="instagram" value="{{ old('instagram') }}"
                                            placeholder="Enter instagram." />
                                        @error('instagram')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="twitter" class="form-label">Twitter</label>
                                        <input type="text"
                                            class="form-control @error('twitter') is-invalid @enderror"
                                            id="twitter" name="twitter" value="{{ old('twitter') }}"
                                            placeholder="Enter twitter." />
                                        @error('twitter')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="text"
                                            class="form-control @error('facebook') is-invalid @enderror"
                                            id="facebook" name="facebook" value="{{ old('facebook') }}"
                                            placeholder="Enter facebook." />
                                        @error('facebook')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
