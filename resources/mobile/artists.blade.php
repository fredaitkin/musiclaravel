@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            Please fix the following errors
        </div>
    @endif

    @if (isset($message))
        <div class="alert alert-warning ml-3 w-25" role="alert">
            {{ $message }}
        </div>
    @endif

    <div class="panel-body">
        <form action="/artists/search" method="POST" role="search">
            {{ csrf_field() }}
            <div class="input-group col-sm-6 pb-2">
                <input type="text" class="form-control mr-1" name="q"placeholder="Search artists" @if (!empty($q)) value="{{ $q }}" @endif>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
    </div> 

    @if ($artists->count() > 0)

        <table class="table table-striped mysounds-table mobile-table">

            <thead>
                <th>Artist</th>
                <th>&nbsp;</th>
            </thead>

            <tbody>
                @foreach ($artists as $artist)
                    <tr class="mobile-mysounds-tr">
                        <td>
                            {{ csrf_field() }}
                            <a href="/artist/{{ $artist->id }}">{{ $artist->artist }}</a>
                        </td>
                        <td>
                           <input type="button" class="btn btn-link btn-mysounds btn-mobile" name="play_songs" id="play-songs-{{ $artist->id }}" value="play songs">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class='mobile ml-2'>
            {{ $artists->onEachSide(1)->links() }} 
        </div>
    @endif

@endsection

@section('scripts')
    <script src="{{ asset('js/artist.js') }}"></script>
@endsection
