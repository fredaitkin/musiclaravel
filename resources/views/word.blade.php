@extends('layouts.app')

@section('content')

    <div class="panel-body mysound-submit-form-div">

        <h2 class="col-sm-12">Edit Word</h2>

        @include('common.errors')

        <form action="/word-cloud" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="col-sm-3 pb-2">
                <label for="word" class="control-label">Word</label>
                <div>
                    <input type="text" name="word" id="word" class="form-control" @if ( ! empty($word_cloud->word)) value="{{ $word_cloud->word }}" @endif>
                </div>
            </div>

            <div class="col-sm-3 pb-2">
                <label for="is_word" class="control-label">Is Word</label>
                <div>
                    <input type="checkbox" name="is_word" id="is_word" @if (!empty($word_cloud->is_word) && ($word_cloud->is_word)) checked @endif>
                </div>
            </div>

            <div class="col-sm-3 pb-2">
                <label for="categories" class="control-label">Categories</label>
                <textarea type="text" name="category_display" id="category_display" class="form-control" rows="2">@if (old('category_display')){{ old('category_display') }}@elseif(!empty($word_cloud->category_display)){{ $word_cloud->category_display }}@endif</textarea>
                <input type="hidden" name="category_ids" id="category_ids" class="form-control" value=@if (old('category_ids')) {{ old('category_ids') }} @elseif (!empty($word_cloud->category_ids)) {{ $word_cloud->category_ids }} @endif>
            </div>

            <div class="col-sm-3 pb-2">
                <label for="variant_of" class="control-label">Variant of</label>
                <div>
                    <input type="text" name="variant" id="variant" class="form-control" @if ( ! empty($word_cloud->variant)) value="{{ $word_cloud->variant }}" @endif>
                    <input type="hidden" name="variant_of" id="variant_of" @if ( ! empty($word_cloud->variant_of)) value="{{ $word_cloud->variant_of }}" @endif>
                </div>
            </div>

            <div class="col-sm-3 pt-5">
                <input type="hidden" name="id" id="song-id" value="{{ $word_cloud->id }}">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
            </div>

        </form>

    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('js/word_cloud.js') }}"></script>
@endsection