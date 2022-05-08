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


            <div class="border border-dark pt-3 ml-3 mb-4 bg-secondary">

                <h6 class="col-sm-6">Load Songs from Media Library</h6>

                <div class="form-group pt-1">
                    <label for="directory" class="col-sm-6 control-label">Media Library: @if (!empty($media_directory)) {{ $media_directory }} @endif</label>
                </div>
        
                <div class="form-group pl-3 row">

                    <div class="form-group col">
                        <label for="directory" class="control-label">Artist Directory</label>
                        <input type="text" name="artist_directory" id="artist_directory" class="form-control" @if (session()->has('artist_directory')) value=" {{ session()->get('artist_directory') }}" @endif>
 
                    </div>

                    <div class="form-group col-sm-2">
                        <label for="entire_library" class="control-label">Entire Media Library</label>
                        <input type="checkbox" name="entire_library" id="entire_library" class="form-control w-auto">
                    </div>

                    <div class="form-group col-sm-2 mt-auto">
                        <button type="submit" class="btn btn-primary">Load</button>
                    </div>

                </div>

            </div>

            <div class="border border-dark pt-3 ml-3 mb-4 bg-secondary">

                <h6 class="col-sm-6">Load Random Songs</h6>

                <div class="form-group pl-3 row">

                    <div class="form-group col">
                        <label for="directory" class="control-label">Directory</label>
                        <input type="text" name="random_directory" id="random_directory" class="form-control"@if (session()->has('random_directory')) value=" {{ session()->get('random_directory') }}" @endif>
                    </div>

                    <div class="form-group col-sm-2 mt-auto">
                            <button type="submit" class="btn btn-primary">Load</button>
                    </div>

                </div>

            </div>

            <div class="border border-dark pt-3 ml-3 bg-secondary">

                <h6 class="col-sm-6">Get Artist Lyrics</h6>

                <div class="form-group pl-3 row">

                    <div class="form-group col">
                        <label for="directory" class="control-label">Artist</label>
                        <input type="text" name="artist" id="artist" class="form-control"@if (session()->has('artist')) value=" {{ session()->get('artist') }}" @endif>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="exact_match" class="control-label">Exact Match</label>
                        <input type="checkbox" name="exact_match" id="exact_match" class="form-control w-auto">
                    </div>
                    <div class="form-group col">
                        <label for="exempt" class="col-sm-4 control-label">Exempt IDs</label>
                        <input type="text" name="exempt" id="exempt" class="form-control" />
                    </div>

                    <div class="form-group col-sm-2 mt-auto">
                        <button type="button" class="btn btn-primary" id="lyrics">Get</button>
                    </div>

                </div>
            </div>

        </form>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endsection
