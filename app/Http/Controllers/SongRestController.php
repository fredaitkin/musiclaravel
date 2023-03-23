<?php

/**
 * Controller to handle standard REST requests for songs
 *
 * @package Jukebox
 * @author  Melissa Aitkin
 */

namespace App\Http\Controllers;

use App\Jukebox\Song\SongInterface as Song;
use App\Jukebox\Dictionary\WordCloudInterface as WordCloud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Storage;
use League\OAuth2\Client\Grant\RefreshToken;
use Audeio\Spotify\Oauth2\Client\Provider\Spotify;

/**
 * SongRestController handles song REST requests.
 *
 * Standard song REST requests such as get, post
 */
class SongRestController extends Controller
{

    /**
     * The song interface
     *
     * @var App\Jukebox\Song\SongInterface
     */
    private $song;

    /**
     * The wordCloud interface
     *
     * @var App\Jukebox\Dictionary\WordCloudInterface
     */
    private $wordCloud;

    /**
     * Constructor
     *
     * @param App\Jukebox\Song\SongInterface            $song      Song interface
     * @param App\Jukebox\Dictionary\WordCloudInterface $wordCloud WordCloud interface
     */
    public function __construct(Song $song, WordCloud $wordCloud)
    {
        $this->song = $song;
        $this->wordCloud = $wordCloud;
    }

public function thing() {

          $oauthProvider = new Spotify(array(
            'clientId' => getenv('SPOTIFY_CLIENT_ID'),
            'clientSecret' => getenv('SPOTIFY_CLIENT_SECRET'),
            'redirectUri' => 'http://localhost:8000'
        ));

  self::$accessToken = $oauthProvider->getAccessToken();

        // self::$accessToken = $oauthProvider->getAccessToken(new RefreshToken(), array(
            // 'refresh_token' => getenv('SPOTIFY_REFRESH_TOKEN')
        // ))->accessToken;

}
    /**
     * Display songs
     *
     * @param Illuminate\Http\Request $request Request object
     *
     * @return mixed
     */
    public function index(Request $request)
    {echo "GDAY";
    $this->thing();
      // public const API_URL = 'https://api.spotify.com';

// $response = Http::withHeaders([
    // 'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret),
// ])->post('https://accounts.spotify.com/api/token', [
    // 'name' => 'Taylor',
// ]);
/*
$response = Http::withBasicAuth($client_id, $client_secret)
->accept('application/json')
->post('https://accounts.spotify.com/api/token', [
    'form' => [
        'grant-type' => 'client_credentials',
    ]
]);
var_dump($response->status());
var_dump($response->body());
*/
        $songs = $this->song->all($request);
        if (empty($request->all()) || ($request->has('page') && !isset($request->genres))):
            return view('songs', ['songs' => $songs]);
        endif;

        if (isset($request->lyrics) && isset($request->id)):
            return view('lyrics', ['song' => $this->song->get($request->id)]);
        endif;

        if (isset($request->genres)):
            return view('genres', ['genres' => $this->song->getGenres()]);
        endif;

        return $songs;
    }

    /**
     * Store a newly or update a song in the database
     *
     * @param Illuminate\Http\Request $request Request object
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if (isset($request->lyric_update)):
            // Lyrics only update.
            $song = $this->song->get($request->id);
            if ($request->lyrics != $song->lyrics):
                $this->wordCloud->process($request->lyrics, 'subtract', $request->id);
                $this->wordCloud->process($request->lyrics, 'add', $request->id);
                $song->lyrics = $request->lyrics;
                $song->save();
            endif;
        else:
            $this->song->createOrUpdate($request);
        endif;

        return redirect('/songs');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id Song id
     *
     * @return Response
     */
    public function edit($id)
    {
        $song = $this->song->get($id ?? null);

        if (! $song):
            abort(404);
        endif;

        // Add this to the get function?
        if (! empty($song->cover_art)):
            $cover_art = unserialize($song->cover_art);
            $cover_art = $cover_art['api'];
        endif;
        if (empty($cover_art)):
            $cover_art = '/image/cover/' . $song->id;
        endif;
        return view(
            'song', [
            'song'          => $song,
            'title'         => $song->title,
            'cover_art'     => $cover_art,
            'artists'       => json_encode($song->artists),
            'file_types'    => config('audio_file_formats'),
            'song_exists'   => Storage::disk(config('filesystems.partition'))->has(config('filesystems.media_directory') . $song->location),
            ]
        );
    }

}
