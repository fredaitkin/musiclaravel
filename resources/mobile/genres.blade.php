@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            Please fix the following errors
        </div>
    @endif

    @if (isset($message))
        <p>{{ $message }}</p>
    @endif

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

    <div class='ml-2'>
        {{ $genres->withPath('songs?genres=true')->onEachSide(1)->links() }}
    </div>

@endsection
