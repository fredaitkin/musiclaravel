@extends('layouts.app')

@section('content')

    <div class="panel-body mysound-submit-form-div">

        <h2 class="col-sm-12">{{ $word_cloud->word }}</h2>

        @include('common.errors')

        <form action="/word-cloud" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="col-sm-3">
                <label for="is_word" class="control-label">Is Word</label>
                <div>
                    <input type="checkbox" name="is_word" id="is_word" @if (!empty($word_cloud->is_word) && ($word_cloud->is_word)) checked @endif>
                </div>
            </div>

            <div class="col-sm-3">
                <label for="category" class="control-label">Category</label>
                <div>
                    <input type="text" name="category" id="category" class="form-control" @if ( ! empty($word_cloud->category)) value="{{ $word_cloud->category }}" @endif>
                </div>
            </div>

            <div class="col-sm-3">
                <label for="variant_of" class="control-label">Variant of</label>
                <div>
                    <input type="text" name="variant_of" id="variant_of" class="form-control" @if ( ! empty($word_cloud->variant_of)) value="{{ $word_cloud->variant_of }}" @endif>
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