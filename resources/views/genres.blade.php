@extends('layouts.app')

@section('content')

    <div class="panel-body">

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                Please fix the following errors
            </div>
        @endif

        @if (isset($message))
            <p>{{ $message }}</p>
        @endif

        <div class="col-sm-3">
            <h5>Genres</h5>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col">
                        <table class="table table-striped mysounds-table">

                            <thead>
                                <th>Genre</th>
                                <th>&nbsp;</th>
                            </thead>

                            <tbody>
                                @foreach ($genres as $genre)
                                    <tr class="mysounds-tr">
                                        <td class="table-text">
                                            <div name="genre-title">{{ $genre->genre }}</div>
                                        </td>
                                        <td>
                                            {{ csrf_field() }}
                                            <a href="#" name="play_genre">play</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $genres->withPath('songs?genres=true')->links() }}
                    </div>
                    <div class="col" style="background: linear-gradient(to top, black, transparent 90%);">
                        <img class="w-100 h-100" src="{{ asset('img/skeleton.jpg') }}" alt="genre">
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/song.js') }}"></script>
@endsection
