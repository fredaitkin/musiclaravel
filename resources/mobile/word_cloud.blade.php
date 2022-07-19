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
                <form class="form-inline" method="GET">
                  <div class="form-group ml-3 mb-2">
                    <label for="filter" class="col-sm-3 col-form-label">Filter</label>
                    <input type="text" class="form-control" id="filter" name="filter" placeholder="Word/Category" value="{{ $filter }}">
                  </div>
                  <button type="submit" class="btn btn-primary ml-1 mt-4">
                    <i class="fa fa-search"></i>
                  </button>
                </form>

                <table class="table table-striped mysounds-table">

                    <thead>
                        <th scope='col' class='sortable'>@sortablelink('word')</th>
                        <th scope='col' class='sortable'>@sortablelink('category', 'Cat')</th>
                        <th scope='col' class='sortable'>@sortablelink('count', 'Cnt')</th>
                        <th></th>
                        <th></th>
                     </thead>

                    <tbody>
                        @foreach ($word_cloud as $word)
                            <tr class="mysounds-tr">
                                <td class="table-text">
                                    <div>
                                        {{ csrf_field() }}
                                        <a href="/word-cloud/{{ $word->id }}?page={{ $word_cloud->currentPage() }}">{{ $word->word }}</a>
                                    </div>
                                </td></div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $word->category_display }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $word->count }}</div>
                                </td>
                                <td>
                                   <input type="button" class="btn btn-link btn-mysounds" name="songs" id="songs-{{ $word->id }}" value="songs">
                                </td>
                                <td>
                                    @if ($word->is_word)
                                        <input type="button" class="btn btn-link btn-mysounds" name="dictionary" id="dictionary-{{ $word->word }}" value="dictionary">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="ml-2">
                    {{ $word_cloud->appends(['filter' => $filter])->links() }}
                </div>

                <form class="form-inline" method="GET">
                    <div class="input-group w-50 mt-1">
                        <input type="text" class="form-control" id="page" name="page" size=10>
                        <input type="submit" class="btn btn-secondary" id="go" value="Go">
                    </div>
                    </form>
                </div>
            </div>
        </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/dictionary.js') }}"></script>
    <script src="{{ asset('js/word_cloud.js') }}"></script>
@endsection

@section('styles')
    <link href="{{ asset('css/word-cloud.css') }}" rel="stylesheet">
@endsection