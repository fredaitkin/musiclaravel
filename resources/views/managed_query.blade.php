@extends('layouts.app')

@section('content')

    <div class="panel-body">

        <h2 class="col-sm-3">Managed Queries</h2>

        @include('common.errors')

        @if (isset($msg))
            <div>{{ $msg }}</div>
        @endif

        <form action="/managed-query" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="ml-3">

                <div class="form-group">

                    <div class="row pl-3 pb-1">
                        <div class="col-md-3">
                            <label for="predefined_query" class="control-label">Predefined Queries</label>
                            <select class="form-control" name="predefined_query" id="predefined_query">
                                @foreach ($queries as $key => $query)
                                    <option value="{{ $key }}" @if ( ! empty($predefined_query) && ($key == $predefined_query)) selected @endif>{{ $query }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row pl-3 pb-2">
                        <div class="col-md-3">
                            <label for="params" class="control-label">Params</label>
                            <input type="input" name="params" id="params" class="form-control" @if (!empty($params))value="{{ $params }}"@endif>
                        </div>
                    </div>

                    <div>
                        @isset($results)
                            @if (count($results) > 1)
                                <table class="table mysounds-table">
                                    <tbody>
                                        @foreach ($results as $row)
                                            <tr class="mysounds-tr">
                                                @foreach ($row as $col)
                                                    <td class="table-text"><div name="col">{{ $col }}</div></td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endisset
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
