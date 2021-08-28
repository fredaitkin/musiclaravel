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
            <h5>Word Cloud</h5>
        </div>

        <div class="panel panel-default">

            <div class="panel-body">
                @if ($word_cloud->count() > 0)
                    <table class="table table-striped mysounds-table">

                        <thead>
                            <th>Word</th>
                            <th>Category</th>
                            <th>Count</th>
                         </thead>

                        <tbody>
                            @foreach ($word_cloud as $word)
                                <tr class="mysounds-tr">
                                    <td class="table-text">
                                        <div>{{ $word->word }}</div>
                                    </td>
                                    <td class="table-text">
                                        <div>{{ $word->category }}</div>
                                    </td>
                                    <td class="table-text">
                                        <div>{{ $word->count }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                {{ $word_cloud->links() }}
            </div>
        </div>

@endsection