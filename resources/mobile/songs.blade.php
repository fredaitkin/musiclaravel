@extends('layouts.app')

@section('content')

    <div class="panel-body">

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

        <div class="col-sm-3">
            <h5>Current Songs</h5>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <form action="/songs/search" method="POST" role="search">
                    {{ csrf_field() }}
                    <div class="input-group col-sm-6 pb-2">
                        <input type="text" class="form-control" name="q" placeholder="Search songs"  @if (!empty($q)) value="{{ $q }}" @endif>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                </div>
                </form>
            </div>

            <div class="panel-body">
                @if (isset($songs) && $songs->count() > 0)
                    <table class="table table-striped mysounds-table">

                        <thead>
                            <th>Title</th>
                            <th>Artist</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </thead>

                        <tbody>
                            @foreach ($songs as $song)
                                <tr class="mysounds-tr">
                                    <td class="table-text">
                                        <div>{{ $song->title }}</div>
                                    </td>
                                    <td class="table-text">
                                        <div>
                                            <a href="/artist/{{ $song->artists[0]->id }}">{{ $song->artists[0]->artist }} @if($song->artists[0]->artist == 'Compilations') - {{ $song->notes}} @endif</a>
                                        </div>
                                    </td>
                                    <td>
                                        {{ csrf_field() }}
                                        <a href="/song/{{ $song->id }}">edit</a>
                                    </td>
                                    <td>
                                       <input type="button" class="btn btn-link btn-mysounds" name="play" id="play-{{ $song->id }}" value="play">
                                    </td>
                                    <td>
                                       <input type="button" class="btn btn-link btn-mysounds" name="play_album" id="play-album-{{ $song->id }}" value="play album">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $songs->links() }}
                @endif
            </div>
        </div>

        <div>
            @if (Route::has('login'))
                <div class="col-sm-3">
                    @auth
                        <a href="{{ url('/song') }}">Add</a>
                    @endauth
                </div>
            @endif
        </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/utf8/3.0.0/utf8.min.js"></script>
    <script src="{{ asset('js/playlist.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

@endsection

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
