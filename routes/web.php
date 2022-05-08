<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('404', function () {
    return abort(404);
});

Route::middleware(['auth'])->group(function () {

    // Home route

    Route::get('home', 'HomeController@index');

    // Song Routes

    Route::get('songs', 'SongRestController@index');

    Route::post('song', 'SongRestController@store');

    Route::get('song/{id}', 'SongRestController@edit');

    Route::any('songs/search', 'SongResourceController@search');

    Route::get('song', 'SongResourceController@add');

    Route::get('song/play/{id}', 'SongResourceController@play')->name('song.play');

    // Artist Routes

    Route::get('artists', 'ArtistRestController@index');

    Route::post('artist', 'ArtistRestController@store');

    Route::get('artist/{id}', 'ArtistRestController@edit');

    Route::delete('artist/{id}', 'ArtistRestController@destroy')->name('artist.destroy');

    Route::any('artists/search', 'ArtistResourceController@search');

    Route::get('artist-autocomplete', 'ArtistResourceController@autocomplete');

    Route::get('artist/songs/{id}', 'ArtistResourceController@songs');

    Route::get('artist', 'ArtistResourceController@add');

    // Playlist routes

    Route::get('playlists', 'PlaylistRestController@index');

    Route::post('playlists', 'PlaylistRestController@store');

    Route::delete('playlists/{playlist}', 'PlaylistRestController@delete')->name('playlists.delete');

    // Genres routes
    Route::get('genres', 'GenreRestController@index');

    Route::get('genres/songs', 'GenreResourceController@songs');

    // Lyric routes
    Route::get('lyrics/{id}', 'LyricRestController@index');

    Route::post('lyrics', 'LyricRestController@store');

    // Image routes
    Route::get('image/cover/{id}', 'ImageResourceController@coverArt');

    // Word Cloud routes
    Route::get('word-cloud', 'WordCloudRestController@index');

    Route::get('word-cloud/{id}', 'WordCloudRestController@edit');

    Route::post('word-cloud', 'WordCloudRestController@store');

    Route::post('word-cloud-autocomplete', 'WordCloudResourceController@autocomplete');

    // Category routes
    Route::get('categories/ajax', 'CategoryResourceController@categories');

    Route::get('dictionary', 'DictionaryResourceController@dictionary');

});

Route::group(['middleware' => 'role:super-user'], function() {

    // Utilties/Configuration Routes

    Route::get("utilities", ["uses" => "UtilitiesRestController@index"])->name('utilities.utilities');

    Route::post("load", ["uses" => "UtilitiesResourceController@loadSongs"])->name('utilities.load');

    Route::get("settings", "SettingsRestController@index");

    Route::post('settings', 'SettingsRestController@store');

    Route::get('query', 'QueryRestController@index');

    Route::post('query', 'QueryResourceController@query');

    Route::get('managed-query', 'ManagedQueryRestController@index');

    Route::post('managed-query', 'ManagedQueryResourceController@query');
});

Auth::routes();
