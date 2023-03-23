@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            Please fix the following errors
        </div>
    @endif

    @if (isset($message))
        <div class="alert alert-warning ml-3" role="alert">
            {{ $message }}
        </div>
    @endif

    <form class="form-inline" method="GET">
        <div class="d-flex flex-row ml-3">
            <label for="filter" class="control-label mr-1">Filter</label>
            <input type="text" class="form-control" id="filter" name="filter" placeholder="Word/Category" value="{{ $filter }}">
            <button type="submit" class="btn btn-primary ml-1 mb-2">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>

    <table class="table table-striped mysounds-table mobile-table">

        <thead>
            <th scope='col'>Word</th>
            <th scope='col'>Category</th>
            <th scope='col'>Count</th>
            <th></th>
            <th></th>
         </thead>

        <tbody>
            @foreach ($word_cloud as $word)
                <tr class="mobile-mysounds-tr">
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
                       <input type="button" class="btn btn-link btn-mysounds btn-mobile" name="songs" id="songs-{{ $word->id }}" value="songs">
                    </td>
                    <td>
                        @if ($word->is_word)
                            <input type="button" class="btn btn-link btn-mysounds btn-mobile" name="dictionary" id="dictionary-{{ $word->word }}" value="dictionary">
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mobile ml-2">
        {{ $word_cloud->appends(['filter' => $filter])->onEachSide(1)->links() }}
    </div>

    <form method="GET">
        <div class="d-flex mt-1 w-50">
            <input type="text" class="form-control" id="page" name="page" size=10>
            <input type="submit" class="btn btn-primary" id="go" value="Go">
        </div>
    </form>

@endsection

@section('scripts')
    <script src="{{ asset('js/dictionary.js') }}"></script>
    <script src="{{ asset('js/word_cloud.js') }}"></script>
@endsection

@section('styles')
    <link href="{{ asset('css/word-cloud.css') }}" rel="stylesheet">
@endsection