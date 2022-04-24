<?php

namespace App\Jukebox\Song;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Song implements SongInterface
{

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * The constructor method.
     *
     * @param \Illuminate\Http\Request  $request
     */
    function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a song.
     *
     * @param Request $request
     */
    public function createOrUpdate($request)
    {
        $validator = $request->validate([
            'title' => 'required|max:255',
            'album' => 'required|max:255',
            'year'  => 'required|integer',
        ]);

        // Get a new model instance.
        $model = new SongModel();

        $model->title = $this->request->title;
        $model->album = $this->request->album;
        $model->year = $this->request->year;
        $model->file_type = $this->request->file_type;
        $model->track_no = $this->request->track_no;
        $model->genre = $this->request->genre;
        $model->location = $this->request->location;
        $model->filesize = $this->request->filesize ?? 0;
        $model->composer = $this->request->composer;
        $model->playtime = $this->request->playtime;
        $model->notes = $this->request->notes;

        if (isset($this->request->id)):
            $model->id = $this->request->id;
            $model->update();
        else:
            $model->save();
        endif;

        // Make any updates to artist/s
        $existing_artists = [];
        foreach($model->artists as $artist) {
            $existing_artists[] = $artist->id;
        }

        if (empty($this->request->artists)):
            $this->request->artists = [];
        endif;
        $inserts = array_diff($this->request->artists, $existing_artists);
        foreach($inserts as $artist):
            $model->artists()->attach(['artist' => $artist]);
        endforeach;
        $deletes = array_diff($existing_artists, $this->request->artists);
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
    public function all()
    {
        return SongModel::paginate(10);
    }

    /**
     * Create a song via the music loading process.
     *
     * @param string path
     * @param string album_name
     * @param integer artist_it
     * @param array ID3 song array
     *
     * @param Request $request
     */
    public static function dynamicStore($path, $album_name, $artist_id, $song)
    {
        // Get a new model instance.
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
            }])
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
    public function getSongTitles(string $album = null)
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

    public function isSong($file) {
        $extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
        return in_array($extension, config('audio_file_formats'));
    }

    public function getArtistAlbums($id) {
        return SongModel::distinct('album')->where(["artist_id" => $id])->get(['album'])->toArray();
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
    * Retrieve album songs by song id.
    *
    * @param int $id
    */
    public function getAlbumSongsBySongID($id) {
        // FIXME handle common album names like Greatest Hits
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
            }]);
        endif;

        if(isset($request->artist)):
            $query->orWhere("songs.notes", '=', $request->artist);
        endif;

        if(isset($request->offset) && isset($request->limit)):
            $query->skip($request->offset)->take($request->limit);
        endif;

        return $query->get();
    }

}
