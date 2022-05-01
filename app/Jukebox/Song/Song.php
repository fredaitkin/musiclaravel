<?php

namespace App\Jukebox\Song;

use Illuminate\Http\Request;

class Song implements SongInterface
{
    /**
     * Create or update a song.
     *
     * @param Request $request
     */
    public function createOrUpdate(Request $request)
    {
        $validator = $request->validate([
            'title' => 'required|max:255',
            'album' => 'required|max:255',
            'year'  => 'required|integer',
        ]);

        if (isset($request->id)):
            $model = SongModel::find($request->id);
        else:
            $model = new SongModel();
        endif;

        $model->title = $request->title;
        $model->album = $request->album;
        $model->year = $request->year;
        $model->file_type = $request->file_type;
        $model->track_no = $request->track_no;
        $model->genre = $request->genre;
        $model->location = $request->location;
        $model->filesize = $request->filesize ?? 0;
        $model->composer = $request->composer;
        $model->playtime = $request->playtime;
        $model->notes = $request->notes;

        $model->save();

        // Make any updates to artist/s
        $existing_artists = [];
        foreach($model->artists as $artist) {
            $existing_artists[] = $artist->id;
        }

        if (empty($request->artists)):
            $request->artists = [];
        endif;
        $inserts = array_diff($request->artists, $existing_artists);
        foreach($inserts as $artist):
            $model->artists()->attach(['artist' => $artist]);
        endforeach;
        $deletes = array_diff($existing_artists, $request->artists);
        foreach($deletes as $artist):
            $model->artists()->detach(['artist' => $artist]);
        endforeach;
    }

    /**
     * Retrieve a song.
     *
     * @param int $id
     */
    public function get($id)
    {
        return SongModel::find($id);
    }

    /**
     * Get a list of all songs.
     *
     * @return array
     */
    public function all($view = true, $constraints = array())
    {
        if ($view):
            return SongModel::paginate(10);
        else:
            $query = SongModel::select('songs.*');
            if (isset($constraints['genre'])):
                $query->where('genre', $constraints['genre']);
            endif;
            return $query->get();
        endif;
    }

    /**
     * Create a song via the music loading process.
     *
     * @param string path
     * @param string album_name
     * @param integer artist_it
     * @param array ID3 song array
     *
     */
    public function dynamicStore($path, $album_name, $artist_id, $song)
    {
        $model = new SongModel();

        $model->title = $song->title();
        $model->album = $album_name;
        $model->year = $song->year();
        $model->file_type = $song->fileType();
        $model->track_no = $song->trackNo();
        $model->genre = $song->genre();
        $model->location = $path;
        $model->filesize = $song->fileSize();
        $model->composer = $song->composer();
        $model->playtime = $song->playtime();
        $model->notes = $song->notes();

        $model->save();

        $model->artists()->attach(['artist' => $artist_id]);
    }

    /**
     * Update a song.
     *
     * @param array $song
     */
    public function updateSong(array $song)
    {
        $model = new SongModel();

        if (! isset($song['id']) || ! is_numeric($song['id'])):
            throw new Exception('A numeric song id is required');
        endif;

        $model = $model->find($song['id']);

        if (isset($song['lyrics'])):
            $model->lyrics = $song['lyrics'];
        endif;

        $model->update();
    }

    /**
     * Does the album exist
     *
     * @param integer $id Artist id
     * @param string $album_name Album name
     * @return boolean
     */
    public function doesAlbumExist($id, $album_name)
    {
        $song = SongModel::where('album', $album_name)
            ->with(['artists' => function($q) use ($id) {
                $q->where('artist_id', '=', $id);
            }],)
            ->first();
        return isset($song);
    }

    /**
     * Does the song exist
     *
     * @param integer $id Artist id
     * @param string $title Song title
     * @return boolean
     */

    public function doesSongExist($id, $title)
    {
        $songs = SongModel::where('title', $title)->get();
        foreach($songs as $song):
            foreach ($song->artists as $artist):
                if ($artist->id == $id):
                    return true;
                endif;
            endforeach;
        endforeach;
        return false;
    }

    /**
    * Returns all song titles
    *
    * @param string $album Restrict via album.
    * @return Collection Eloquent collection of song titles.
    */
    public function getSongTitles($album = null)
    {
        if($album):
            return SongModel::where('album', '=', $album)->get(['title']);
        else:
            return SongModel::all(['title']);
        endif;
    }

    /**
    * Remove the song
    *
    * @param  int  $id
    */
    public function delete($id)
    {
        $model = new SongModel();
        $model = $model->find($id)->delete();
    }

    /**
    * Is this file an audio file type?
    *
    * @param string $file
    */
    public function isSong($file) {
        $extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
        return in_array($extension, config('audio_file_formats'));
    }

    /**
    * Get song genres
    */
    public function getGenres()
    {
        return SongModel::select('genre')->where('genre', '>', '')->groupBy('genre')->get();
    }

    /**
    * Retrieve artist's songs.
    *
    * Retrieves the songs from the artist's albums and compilation albums.
    *
    * @param int $id
    * @param string $artist
    */
    public function getArtistSongs($id, $artist) {
        return SongModel::select('id', 'title')
            ->whereHas('artists', function($q) use ($id) {
                $q->where('artist_id', '=', $id);
            })
            ->orWhere(["notes" => $artist])
            ->orderBy('title')
            ->get();
    }

    /**
    * Retrieve artist's songs from compilations.
    *
    * @param string $artist
    */
    public function getArtistCompilationSongs($artist) {
        return SongModel::select('*')
            ->where(["notes" => $artist])
            ->orderBy('title')
            ->get();
    }

    /**
    * Retrieve album songs by song id.
    *
    * @param int $id
    */
    public function getAlbumSongsBySongID($id) {
        return SongModel::select('id', 'title', 'album')
            ->where('album', function($q2)  use ($id)
                {
                    $q2->from('songs')
                      ->select('album')
                      ->where('id', '=', $id);
                })
            ->get();
    }

    /**
    * Search for songs
    *
    * @param string $query
    */
    public function search($query) {
        return SongModel::select('songs.*')
            ->whereHas('artists', function($q) use($query) {
                $q->where('artist', 'LIKE', '%' . $query . '%');
            })
            ->orWhere('title', 'LIKE', '%' . $query . '%')
            ->orWhere('album', 'LIKE', '%' . $query . '%')
            ->orWhere('songs.notes', 'LIKE', '%' . $query . '%')
            ->paginate()
            ->appends(['q' => $query])
            ->setPath('');
    }

    /**
    * Get songs by lyric
    *
    * @param string $lyric
    */
    public function getSongsByLyric($lyric) {
        return SongModel::where('lyrics', 'LIKE', "%{$lyric}%")->get();
    }

    /**
     * Retrieve songs via Request params
     *
     * @param Request $request
     */
    public function songs(Request $request)
    {
        if (isset($request->id)):
            $query = SongModel::select('songs.*')->with('artists:artist')->where('id', '=', $request->id);
        elseif (isset($request->all)):
            $query = SongModel::select('songs.*')->with('artists:artist');   
        else:
            $query = SongModel::select('id', 'title', 'lyrics')->with('artists:artist');
        endif;

        if(isset($request->album)):
            $query->where('album', '=', $request->album);
            if (isset($request->id) && $request->album == 'true'):
                // FIXME handle common album names like Greatest Hits
                $id = $request->id;
                $query = SongModel::select('id', 'title', 'album')
                    ->where('album', function($q2)  use ($id)
                        {
                            $q2->from('songs')
                              ->select('album')
                              ->where('id', '=', $id);
                        });
            endif;
        endif;

        if(isset($request->artist_id)):
            $artist_id = $request->artist_id;
            $query = SongModel::with(['artists' => function($q) use ($artist_id) {
                $q->where('artist_id', '=', $artist_id);
            }],);
        endif;

        if(isset($request->artist)):
            $query->orWhere("songs.notes", '=', $request->artist);
        endif;

        if(isset($request->offset) && isset($request->limit)):
            $query->skip($request->offset)->take($request->limit);
        endif;

        return $query->get();
    }

    /**
     * Retrieve songs with English lyrics.
     *
     * @param array $ids
     *   Song ids.
     */
    public function retrieveEnglishLyrics(array $ids = null)
    {
        $query = SongModel::select('songs.id', 'title', 'lyrics')
            ->join('artist_song', 'songs.id', '=', 'artist_song.song_id')
            ->whereNotIn('songs.id', [
                908, 911, 1273, 1425, 2225, 3966, 3994, 4145, 4146, 4885, 8587, 4856, 9473, 9741,
            ])
            ->whereNotIn('artist_song.artist_id', [
                23, 84, 197, 209, 280, 469, 510, 611, 763, 802, 821, 838, 841, 846, 1453, 1516,
            ])
            ->whereNotIn('album', [
                'Turkish Groove', 'African Women', 'Bocelli Greatest Hits', 'Buena Vista Social Club', 'Everything Is Possible!',
                "Edith Piaf - 20 'French' Hit Singles",
            ])
            ->whereNotIn('lyrics', ['unavailable', 'Instrumental', 'inapplicable']);

        if ($ids):
            $query->whereIn('songs.id', $ids);
        endif;

        return $query->get()->toArray();
    }

    /**
     * Retrieve songs via array params
     *
     * @param array $constraints
     */
    public function retrieveSongs(array $constraints = [])
    {
        $query = SongModel::select('songs.*')->with('artists:artist');
        if (isset($constraints['id'])):
            $query->where('songs.id', $constraints['id']);
        endif;
        if (isset($constraints['ids'])):
            $query->whereIn('songs.id', $constraints['ids']);
        endif;
        if (isset($constraints['cover_art_empty'])):
            $query->whereNull('songs.cover_art');
        endif;
        return $query->get();
    }
}
