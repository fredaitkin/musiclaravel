@extends('layouts.app')

@section('content')

    <div class="panel-body mysound-submit-form-div">

        <h2 class="col-sm-6 green">{{ $playlist }}</h2>

        @include('common.errors')

        <div>
             <input type="hidden" name="redirects_to" value="{{ URL::previous() }}"/>
        </div>

        <table class="table table-striped mysounds-table">

            <thead>
                <th>Song</th>
                <th>&nbsp;</th>
            </thead>

            <tbody>
                @foreach ($songs as $song)
                    <tr class="mysounds-tr">
                        <td class="table-text">
                            <div name="song">{{ $song->title }}</div>
                        </td>
                        <td>
                            <form action="/playlists/{{ $playlist }}/{{ $song->id }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                 <a href="javascript:;" onclick="parentNode.submit();">delete</a>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection