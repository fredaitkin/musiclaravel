@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Queries</h2>

        @include('common.errors')

        @if (isset($msg))
            <div>{{ $msg }}</div>
        @endif

        <form action="/query" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="ml-3">

                <div class="form-group">

                    <div class="pb-1">
                        <label for="myquery" class="col-sm-3 control-label">Query</label>
                        <textarea name="myquery" id="myquery" class="form-control" rows="3" cols="50">@if (!empty($myquery)){{ $myquery }}@endif</textarea>
                    </div>
                    <div class="row pb-1">
                        <div class="col-md-3">
                            <label for="predefined_query" class="control-label">Predefined Queries</label>
                            <select class="form-control" name="predefined_query" id="predefined_query">
                                @foreach ($queries as $key => $query)
                                    <option value="{{ $key }}" @if ( ! empty($predefined_query) && ($key == $predefined_query)) selected @endif>{{ $query }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row pb-1">
                        <div class="col-sm-1"><label for="show_cols" class="control-label">Show columns</label></div>
                        <div class="col-sm-1"><input type="checkbox" name="show_cols" id="show_cols" class="form-control" @if ($show_cols) checked="{{ $show_cols }}" @endif></div>
                    </div>
                    <div>
                        <textarea name="results" id="results" class="form-control" rows="18" cols="50">@if (!empty($results)){{ $results }}@endif</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-primary">Run</button>
                    </div>
                </div>

            </div>

        </form>

    </div>
@endsection
