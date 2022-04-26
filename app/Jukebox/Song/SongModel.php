<?php

namespace App\Jukebox\Song;

use Illuminate\Database\Eloquent\Model;

class SongModel extends Model
{

    protected $table = 'songs';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $id;

    /**
     * The title.
     *
     * @var string
     */
    protected $title;

    /**
     * The album.
     *
     * @var string
     */
    protected $album;

    /**
     * The year.
     *
     * @var integer
     */
    protected $year;

    /**
     * The file type.
     *
     * @var string
     */
    protected $file_type;

    /**
     * The track number.
     *
     * @var string
     */
    protected $track_no;

    /**
     * The genre.
     *
     * @var string
     */
    protected $genre;

    /**
     * The song file location.
     *
     * @var string
     */
    protected $location;

    /**
     * The composer.
     *
     * @var string
     */
    protected $composer;

    /**
     * The song length.
     *
     * @var string
     */
    protected $playtime;

    /**
     * The song file size.
     *
     * @var integer
     */
    protected $filesize;

    /**
     * Created date.
     *
     * @var datetime
     */
    protected $created_at;

    /**
     * Updated date.
     *
     * @var datetime
     */
    protected $updated_at;

    /**
     * Notes about the song.
     *
     * @var string
     */
    protected $notes;

    /**
     * Song lyrics.
     *
     * @var string
     */
    protected $lyrics;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
    * Song artists
    */
    public function artists()
    {
        return $this->belongsToMany('App\Jukebox\Artist\ArtistModel', 'artist_song', 'song_id', 'artist_id');
    }

}
