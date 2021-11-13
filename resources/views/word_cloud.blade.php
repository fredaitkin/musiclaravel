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
                  <button type="submit" class="btn btn-primary mb-2">Go</button>
                </form>

                <table class="table table-striped mysounds-table">

                    <thead>
                        <th scope='col' class='sortable'>@sortablelink('word')</th>
                        <th scope='col' class='sortable'>@sortablelink('is_word', 'Is Word')</th>
                        <th scope='col' class='sortable'>@sortablelink('category')</th>
                        <th scope='col' class='sortable'>@sortablelink('variant_of', 'Variant Of')</th>
                        <th scope='col' class='sortable'>@sortablelink('count')</th>
                        <th></th>
                     </thead>

                    <tbody>
                        @foreach ($word_cloud as $word)
                            <tr class="mysounds-tr">
                                <td class="table-text">
                                    <div>
                                        {{ csrf_field() }}
                                        <a href="/word-cloud/{{ $word->id }}">{{ $word->word }}</a>
                                    </div>
                                </td></div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $word->is_word }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $word->categories_display }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $word->variant }}</div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $word->count }}</div>
                                </td>
                                <td>
                                   <input type="button" class="btn btn-link btn-mysounds" name="songs" id="songs-{{ $word->id }}" value="songs">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $word_cloud->appends(\Request::except('page'))->render() }}
            </div>
        </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/word_clouds.js') }}"></script>
@endsection
