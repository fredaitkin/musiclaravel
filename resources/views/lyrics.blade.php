@extends('layouts.app')

@section('content')

    <div class="panel-body mysound-submit-form-div">

        <h2 class="col-sm-12">{{ $song->title }} - {{ $song->artists[0]->artist }}</h2>

        @include('common.errors')

        <form action="/song" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="form-group">
                <div>
                    <textarea name="lyrics" id="lyrics" class="form-control" cols="30" rows="35">{{ $song->lyrics }}</textarea>
                </div>
            </div>

            <div class="form-group">
                @if ( ! empty($song->id))
                    <div class="col-sm-offset-3 col-sm-6">
                        <input type="hidden" name="id" id="id" value="{{ $song->id }}">
                        <input type="hidden" name="lyric_update" id="lyric_update" value="true">
                        <input type="hidden" name="artist" id="artist" value="{{ $song->artists[0]->artist }}">
                        <input type="hidden" name="song" id="song" value="{{ $song->title }}">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-primary" name="lyric_retrieve">Get</button>
                        <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                    </div>
                @endif
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/lyrics.js') }}"></script>
@endsection