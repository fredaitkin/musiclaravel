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

    Route::delete('song/{id}', 'SongRestController@destroy');

    Route::any('songs/search', 'SongResourceController@search');

    Route::get('song', 'SongResourceController@add');

    Route::get('song/play/{id}', 'SongResourceController@play')->name('song.play');

    // Artist Routes

    Route::get('artists', 'ArtistRestController@index');

    Route::post('artist', 'ArtistRestController@store');

    Route::get('artist/{id}', 'ArtistRestController@edit');

    Route::delete('artist/{id}', 'ArtistRestController@destroy')->name('artist.destroy');

    Route::any('artists/search', 'ArtistResourceController@search');

    Route::get('artist-select-ajax', 'ArtistResourceController@artist_ajax');

    Route::get('artist/songs/{id}', 'ArtistResourceController@songs');

    // Playlist routes

    Route::get('playlists', 'PlaylistController@index');

    Route::post('playlists', 'PlaylistController@save');

    Route::delete('playlists/{playlist}', 'PlaylistController@destroy')->name('playlists.destroy');

    Route::get('playlists/songs', 'PlaylistController@songs');

    // Genres routes
    Route::get('genres', 'GenreController@index');

    // Lyric routes
    Route::get('lyrics/{id}', 'LyricController@show');

    Route::post('lyrics', 'LyricController@store');

    // Image routes
    Route::get('cover/{id}', 'ImageAPIController@coverArt');

    // Word Cloud routes
    Route::get('word-cloud', 'WordCloudController@index');

    Route::get('word-cloud/{id}', 'WordCloudController@edit');

    Route::post('word-cloud', 'WordCloudController@store');

    Route::post('word-cloud-autocomplete', 'WordCloudController@autocomplete');

    // Category routes
    Route::get('categories/ajax', 'CategoryController@categories');

});

Route::middleware(['auth'])->prefix('internalapi')->group(function () {

    // Routes that can be called both internally and externally
    // Route::get('songs', 'SongController@all');

    // Route::get('songs/{id}', 'SongController@song');

    // Route::get('playlists', 'PlaylistController@playlists');

    // Route::get('playlists/songs', 'PlaylistController@songs');

    // Route::post('playlists', 'PlaylistController@save');

    // Route::get('genres/songs', 'GenreController@songs');

    // Route::get('artist/songs/{id}', 'ArtistController@songs');

    // Route::get('word-cloud', 'WordCloudController@songs');

    // Route::get('lyrics/artist', 'LyricController@artist');

    // Route::get('dictionary', 'DictionaryController@dictionary');

 });


Route::group(['middleware' => 'role:super-user'], function() {

    // Utilties/Configuration Routes

    Route::get("utilities", ["uses" => "UtilitiesController@index"])->name('utilities.utilities');

    Route::post("load", ["uses" => "UtilitiesController@loadSongs"])->name('utilities.load');

    Route::get("settings", "SettingsController@index");

    Route::post('settings', 'SettingsController@settings');

    Route::get('query', 'QueryController@index');

    Route::post('query', 'QueryAPIController@query');

    Route::get('managed-query', 'ManagedQueryController@index');

    Route::post('managed-query', 'ManagedQueryController@query');
});

Auth::routes();
