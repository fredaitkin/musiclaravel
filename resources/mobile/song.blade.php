@extends('layouts.app')

@section('content')

    <div class="panel-body mysound-submit-form-div">

        <h2 class="col-sm-12">{{ $title }}</h2>

        @include('common.errors')

        <form action="/song" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if ($song_exists)
            <div class="form-group">
                <div class="col-sm-3">
                    <!--
                    <a href="/song/mobile/play/{{ $song->id }}" target="_blank">play</a>
                    <i class="fa fa-music" style="color:peru;"></i>
                    <i class="fa fa-music" style="color:peru;"></i>
                    <i class="fa fa-music" style="color:peru;"></i>
                -->
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col">
                    <label for="artist" class="control-label">Artist</label>
                    <div class="pb-1">
                        <select class="artists form-control" multiple="multiple" name="artists[]" id="artists"></select>

                    </div>
                </div>
            </div>

            <div class="form-group row">
                <!-- first column -->
                <div class="col">
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="genre" class="control-label">Genre</label>
                            <div class="pb-1">
                                <input type="text" name="genre" id="song-genre" class="form-control" @if ( ! empty($song->genre)) value="{{ $song->genre }}" @endif>
                            </div>
                        </div>

                       <div class="col-sm-2">
                            <label for="year" class="control-label">Year</label>
                            <div class="pb-1">
                                <input type="text" name="year" id="song-year" class="form-control" @if ( ! empty($song->year)) value="{{ $song->year }}" @endif>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <label for="rank" class="control-label">Rank</label>
                            <div class="pb-1">
                                 <select class="form-control" name="rank">
                                    <option value=""></option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" @if ( ! empty($song->rank) && ($song->rank == $i)) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                     </div>
                </div>

                <!-- second column -->
                <div class="col pt-5 pl-3">
                    @if (isset($cover_art))
                        <img src="{{ $cover_art }}" class="css-class" alt="" style="width:90%">
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="album" class="control-label">Album</label>
                    <div class="pb-1">
                        <input type="text" name="album" id="song-album" class="form-control" @if ( ! empty($song->album)) value="{{ $song->album }}" @endif>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="composer" class="control-label">Composer</label>
                    <div class="pb-2">
                        <textarea name="composer" id="composer" class="form-control" rows="1">@if (!empty($song->composer)){{ $song->composer }}@endif</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="last_played" class="control-label">Last Played</label>
                    <div class="pb-1">
                        <input type="text" name="last_played" id="last_played" class="form-control" @if ( ! empty($song->last_played)) value="{{ $song->last_played }}" @endif>
                    </div>
                </div>

                <div class="col">
                    <label for="played" class="control-label">Played</label>
                    <div class="pb-1">
                        <input type="text" name="played" id="played" class="form-control" @if ( ! empty($song->played)) value="{{ $song->played }}" @endif>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="do_not_play" class="control-label">Don't Play</label>
                    <div class="pb-1">
                        <input type="checkbox" name="do_not_play" id="do_not_play" @if (!empty($song->do_not_play) && ($song->do_not_play)) checked @endif>
                    </div>
                </div>
            </div>

            <div class="row pt-2">
                <div class="col">
                    @if ( ! empty($song->id))
                        <input type="hidden" name="id" id="song-id" value="{{ $song->id }}">
                        <input type="hidden" name="artist_json" id="artist_json" value="{{ $artists }}">
                        <input type="hidden" name="title" id="title" value="{{ $song->title }}">
                        <input type="hidden" name="file_type" id="file_type" value="{{ $song->file_type }}">
                        <input type="hidden" name="track_no" id="track_no" value="{{ $song->track_no }}">
                        <input type="hidden" name="location" id="location" value="{{ $song->location }}">
                        <input type="hidden" name="filesize" id="filesize" value="{{ $song->filesize }}">
                        <input type="hidden" name="playtime" id="playtime" value="{{ $song->playtime }}">
                        <input type="hidden" name="notes" id="notes" value="{{ $song->notes }}">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                    @else
                        <button type="submit" class="btn btn-primary">Add Song</button>
                    @endif
                </div>
            </div>

        </form>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/artist.js') }}"></script>
    <script src="{{ asset('js/playlist.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endsection

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
