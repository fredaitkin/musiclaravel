@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Utilities</h2>

        @include('common.errors')

        @if (isset($msg))
            <div class="alert alert-success">{{ $msg }}</div>
        @endif

        @if (session()->has('msg'))
            <div class="alert alert-success">
                {{ session()->get('msg') }}
            </div>
        @endif

        <form action="/load" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="border border-dark pt-3 ml-3 mb-4">

                <h6 class="col-sm-6">Load Songs from Media Library</h6>

                <div class="form-group pt-1">
                    <label for="directory" class="col-sm-6 control-label">Media Library: @if (!empty($media_directory)) {{ $media_directory }} @endif</label>
                </div>

                <div class="form-group">
                    <label for="directory" class="col-sm-3 control-label">Artist Directory</label>

                    <div class="col-sm-6">
                        <input type="text" name="artist_directory" id="artist_directory" class="form-control" @if (session()->has('artist_directory')) value=" {{ session()->get('artist_directory') }}" @endif>
                    </div>
                </div>


                <div class="form-group">
                    <label for="entire_library" class="col-sm-4 control-label">Entire Media Library</label>

                    <div class="col-sm-1">
                        <input type="checkbox" name="entire_library" id="entire_library" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-primary">Load</button>
                    </div>
                </div>

            </div>

            <div class="border border-dark pt-3 ml-3">

                <h6 class="col-sm-6">Load Random Songs</h6>

                <div class="form-group">
                    <label for="directory" class="col-sm-3 control-label">Directory</label>

                    <div class="col-sm-6">
                        <input type="text" name="random_directory" id="random_directory" class="form-control"@if (session()->has('random_directory')) value=" {{ session()->get('random_directory') }}" @endif>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-primary">Load</button>
                    </div>
                </div>

            </div>

            <div class="border border-dark pt-3 ml-3">

                <h6 class="col-sm-6">Get Artist Lyrics</h6>

                <div class="form-group">
                    <label for="directory" class="col-sm-3 control-label">Artist</label>

                    <div class="col-sm-6">
                        <input type="text" name="artist" id="artist" class="form-control"@if (session()->has('artist')) value=" {{ session()->get('artist') }}" @endif>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exact_match" class="col-sm-4 control-label">Exact Match</label>
                    <div class="col-sm-1">
                        <input type="checkbox" name="exact_match" id="exact_match" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exempt" class="col-sm-4 control-label">Exempt IDs</label>
                    <div class="col-sm-6">
                        <input type="text" name="exempt" id="exempt" class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="button" class="btn btn-primary" id="lyrics">Get</button>
                    </div>
                </div>

            </div>

        </form>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/the_works.js') }}"></script>
@endsection
