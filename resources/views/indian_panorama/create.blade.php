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
                            <div class="card-title">Indian Panorama
                                <a href={{ route('indian-panorama.index') }} class="btn btn-warning btn-flat">
                                    Back
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('indian-panorama.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="official_selection_id" class="form-label">
                                            <strong>Official Selection</strong>
                                        </label>
                                        <select name="official_selection_id" id="official_selection_id"
                                            class="form-select @error('official_selection_id') is-invalid @enderror">
                                            <option value="" selected>Select Official Selection</option>
                                            @foreach ($IPOfficialSelections as $IPOfficialSelection)
                                                <option value="{{ $IPOfficialSelection->id }}"
                                                    {{ old('official_selection_id') == $IPOfficialSelection->id ? 'selected' : '' }}>
                                                    {{ $IPOfficialSelection->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('official_selection_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}"
                                            placeholder="Enter title." />
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="directed_by" class="form-label">Directed By</label>
                                        <input type="text"
                                            class="form-control @error('directed_by') is-invalid @enderror" id="directed_by"
                                            name="directed_by" value="{{ old('directed_by') }}"
                                            placeholder="Directed by" />
                                        @error('directed_by')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="country_of_origin" class="form-label">Country Of Origin</label>
                                        <input type="text"
                                            class="form-control @error('country_of_origin') is-invalid @enderror"
                                            id="country_of_origin" name="country_of_origin"
                                            value="{{ old('country_of_origin') }}"
                                            placeholder="Enter country of origin.!!" />
                                        @error('country_of_origin')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <input type="text" class="form-control @error('language') is-invalid @enderror"
                                            id="language" name="language" value="{{ old('language') }}"
                                            placeholder="Enter language" />
                                        @error('language')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" />
                                        <small class="form-text text-muted">Upload an image file (jpg, jpeg, png).</small>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="image_url" class="form-label">Image URL</label>
                                        <input type="text" class="form-control @error('image_url') is-invalid @enderror"
                                            id="image_url" name="image_url" value="{{ old('image_url') }}">
                                        @error('image_url')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- General Sub Category --}}
                                    <div class="col-md-6 mb-3" id="general_sub_category_div" style="display:none;">
                                        <label class="form-label"><strong>Sub Category</strong></label>
                                        <select name="sub_category" id="general_sub_category"
                                            class="form-select @error('sub_category') is-invalid @enderror">
                                            <option value="" selected>Select Sub Category</option>
                                            @foreach ($specialSubCategory as $key => $subCategory)
                                                <option value="{{ $key }}">{{ $subCategory }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- AI-Competition --}}
                                    <div class="col-md-6 mb-3" id="ai_competition_div" style="display:none;">
                                        <label class="form-label"><strong>AI Competition Category</strong></label>
                                        <select name="ai_competition_sub_category" id="ai_competition_sub_category"
                                            class="form-select @error('sub_category') is-invalid @enderror">
                                            <option value="" selected>Select Sub Category</option>
                                            @foreach ($aiCompetitionCategory as $key => $subCategory)
                                                <option value="{{ $key }}">{{ $subCategory }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- AI-Non-Competition --}}
                                    <div class="col-md-6 mb-3" id="ai_non_competition_div" style="display:none;">
                                        <label class="form-label"><strong>AI Non-Competition Category</strong></label>
                                        <select name="ai_non_sub_category" id="ai_non_sub_category"
                                            class="form-select @error('sub_category') is-invalid @enderror">
                                            <option value="" selected>Select Sub Category</option>
                                            @foreach ($aiNonCompetitionCategory as $key => $subCategory)
                                                <option value="{{ $key }}">{{ $subCategory }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="restored_by_nfai" class="form-label">Restored By NFAI</label>
                                        <input type="text"
                                            class="form-control @error('restored_by_nfai') is-invalid @enderror"
                                            id="restored_by_nfai" name="restored_by_nfai"
                                            value="{{ old('restored_by_nfai') }}">
                                        @error('restored_by_nfai')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="num_of_years" class="form-label">Number Of Years</label>
                                        <input type="text"
                                            class="form-control @error('num_of_years') is-invalid @enderror"
                                            id="num_of_years" name="num_of_years" value="{{ old('num_of_years') }}">
                                        @error('num_of_years')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label"><strong>Year</strong></label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror">
                                            <option value="" selected>Select Year</option>
                                            <option value="2025" {{ old('year') == 2025 ? 'selected' : '' }}>2025
                                            </option>
                                            <option value="2024" {{ old('year') == 2024 ? 'selected' : '' }}>2024
                                            </option>
                                            <option value="2023" {{ old('year') == 2023 ? 'selected' : '' }}>2023
                                            </option>
                                            <option value="2022" {{ old('year') == 2022 ? 'selected' : '' }}>2022
                                            </option>
                                        </select>
                                        @error('year')
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        $('#official_selection_id').on('change', function() {
            let value = $(this).val();

            if (value == 4) {
                $('#general_sub_category_div').show();
                $('#ai_competition_div').hide();
                $('#ai_non_competition_div').hide();
            } else if (value == 7) {
                $('#general_sub_category_div').hide();
                $('#ai_competition_div').show();
                $('#ai_non_competition_div').hide();
            } else if (value == 8) {
                $('#general_sub_category_div').hide();
                $('#ai_competition_div').hide();
                $('#ai_non_competition_div').show();
            } else {
                $('#general_sub_category_div').hide();
                $('#ai_competition_div').hide();
                $('#ai_non_competition_div').hide();
            }
        });

    });
</script>
