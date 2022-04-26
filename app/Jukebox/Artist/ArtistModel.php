<?php

namespace App\Jukebox\Artist;

use Illuminate\Database\Eloquent\Model;

class ArtistModel extends Model
{

    protected $table = 'artists';

    /**
     * The primary key for the model.
     *
     * @var integer
     */
    protected $id;

    /**
     * The artist.
     *
     * @var string
     */
    protected $artist;

    /**
     * Is the artist a group.
     *
     * @var bool
     */
    protected $is_group;

    /**
     * The country the artist is from.
     *
     * @var string
     */
    protected $country;

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
     * Members of the band.
     *
     * @var string
     */
    protected $group_members;

    /**
     * Notes about the artist.
     *
     * @var string
     */
    protected $notes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Artist songs
     */
    public function songs()
    {
        return $this->belongsToMany('App\Jukebox\Song\SongModel', 'artist_song', 'artist_id', 'song_id');
    }

}
