@extends('layouts.app')

@section('content')

    <div class="panel-body mysound-submit-form-div">

        <h2 class="col-sm-6 green">{{ $title }}</h2>

        @include('common.errors')

        <form action="/artist" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div>
                 <input type="hidden" name="redirects_to" value="{{ URL::previous() }}"/>
            </div>

            <div class="form-group row w-50 ml-1">
                <div class="col">
                    <label for="artist" class="control-label">Artist</label>
                    <input type="text" name="artist" id="artist-artist" class="form-control" @if ( ! empty($artist->artist)) value="{{ $artist->artist }}" @endif>
                </div>
                <div class="col">
                    <label for="country" class="control-label">Country</label>
                    <select class="form-control" name="country">
                        @foreach ($countries as $country)
                            <option value="{{ $country }}" @if ( ! empty($artist->country) && ($artist->country == $country)) selected @endif>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group row w-50 float-right pl-5">
                <div class="col">
                    @if (isset($artist->photo))
                        <img src="{{ asset("storage/artists/$artist->photo") }}" class="img-thumbnail img-fluid artist-photo" alt="artist photo">
                    @else
                        <img src="{{ asset("storage/black.jpeg") }}" class="img-fluid w-50" alt="black" >
                    @endif
                </div>
            </div>

            @if (isset($albums))
                <div class="form-group">
                    <label for="album" class="col-sm-6 control-label">Albums</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="album" id="album">
                            @foreach ($albums as $album)
                                <option value="{{ $album['album'] }}">{{ $album['album'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="form-group row w-50">
                <div class="col">
                    <label for="location" class="control-label">Based</label>
                    <input name="location" id="location" class="form-control" @if (!empty($artist->location)) value="{{ $artist->location }}"@endif/>
                </div>
            </div>

            <div class="form-group row w-50">
                <div class="col-sm-2">
                    <label for="founded" class="control-label">@if (!empty($artist->is_group) && ($artist->is_group)) Founded @else Active From @endif</label>
                    <input type="text" name="founded" id="founded" class="form-control" value=@if (old('founded')) {{ old('founded') }} @elseif (!empty($artist->founded)) {{ $artist->founded }} @endif>
                </div>
                <div class="col-sm-2">
                    <label for="disbanded" class="control-label">@if (!empty($artist->is_group) && ($artist->is_group)) Disbanded @else Active To @endif</label>
                    <input type="text" name="disbanded" id="disbanded" class="form-control" value=@if (old('disbanded')) {{ old('disbanded') }} @elseif (!empty($artist->disbanded)) {{ $artist->disbanded }} @endif>
                </div>
                <div class="col-sm-2">
                    <label for="is_group" class="control-label">Is Group</label>
                    <div>
                        <input type="checkbox" name="is_group" id="song-is_group" @if (!empty($artist->is_group) && ($artist->is_group)) checked @endif>
                    </div>
                </div>
            </div>

            <div class="form-group row w-50">
                <div class="col">
                    <label for="group_members" class="control-label">Group Members</label>
                    <textarea name="group_members" id="artist-group_members" class="form-control">@if (!empty($artist->group_members)){{ $artist->group_members }}@endif</textarea>
                </div>
            </div>

            <div class="form-group row w-50 ml-1">
                <div class="col">
                    <label for="notes" class="control-label">Notes</label>
                    <textarea name="notes" id="artist-notes" class="form-control">@if (!empty($artist->notes)){{ $artist->notes }}@endif</textarea>
                </div>
            </div>

            @if (isset($songs) && count($songs) > 0)
                <div class="row w-50 float-right pl-5  @if (empty($artist->photo)) no-artist-photo @endif">
                    <div class="col">
                        Songs
                        <ol id="songs" style="list-style-type:none">
                            @foreach ($songs as $song)
                                <li><a href="{{ url('/song') }}/{{ $song->id }}">{{ $song->title }}</a></li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endif

            <div class="form-group row w-50 ml-1">
                <div class="col">
                    <label for="photo" class="control-label">Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>
            </div>

            <div class="form-group">
                @if (!empty($artist->id))
                    <div class="col-sm-offset-3 col-sm-6">
                        <input type="hidden" name="id" id="artist-id" value="{{ $artist->id }}">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                    </div>
                @else
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-primary">Add Artist</button>
                    </div>
                @endif
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/artist.js') }}"></script>
@endsection
