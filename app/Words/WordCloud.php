<?php

namespace App\Words;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WordCloud extends Model
{

    protected $table = 'word_cloud';

    /**
     * The primary key for the model.
     *
     * @var integer
     */
    protected $id;

    /**
     * The word.
     *
     * @var string
     */
    protected $word;

    /**
     * Number of times the word appears in lyrics.
     *
     * @var integer
     */
    protected $count;

    /**
     * Recognized as word by the two dictionary databases.
     *
     * @var bool
     */
    protected $is_word;

    /**
     * Is an acronym.
     *
     * @var bool
     */
    protected $is_acronym;

    /**
     * Is a single letter.
     *
     * @var bool
     */
    protected $is_alphabet;

    /**
     * Is a brand.
     *
     * @var bool
     */
    protected $is_brand;

    /**
     * Is capitalized.
     *
     * @var bool
     */
    protected $is_capitalized;

    /**
     * Is a country.
     *
     * @var bool
     */
    protected $is_country;

    /**
     * Is a day.
     *
     * @var bool
     */
    protected $is_day;

    /**
     * Is a honorific.
     *
     * @var bool
     */
    protected $is_honorific;

    /**
     * Is a made up word.
     *
     * @var bool
     */
    protected $is_made_up;

    /**
     * Is a month.
     *
     * @var bool
     */
    protected $is_month;

    /**
     * Is a name.
     *
     * @var bool
     */
    protected $is_name;

    /**
     * Is an object.
     *
     * @var bool
     */
    protected $is_object;

    /**
     * Is an organisation.
     *
     * @var bool
     */
    protected $is_organisation;

    /**
     * Is a place.
     *
     * @var bool
     */
    protected $is_place;

    /**
     * Is related to religion.
     *
     * @var bool
     */
    protected $is_religion;

    /**
     * Is a state.
     *
     * @var bool
     */
    protected $is_state;

    /**
     * Is a street.
     *
     * @var bool
     */
    protected $is_street;

    /**
     * Is a town or city.
     *
     * @var bool
     */
    protected $is_town;

    /**
     * Is French.
     *
     * @var bool
     */
    protected $is_french;

    /**
     * Is German.
     *
     * @var bool
     */
    protected $is_german;

    /**
     * Is Italian.
     *
     * @var bool
     */
    protected $is_italian;

    /**
     * Is Spanish.
     *
     * @var bool
     */
    protected $is_spanish;

    /**
     * The id of the word this word is the variant of.
     *
     * @var integer
     */
    protected $variant_of;

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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Word cloud songs
     */
    public function songs()
    {
        return $this->belongsToMany('App\Music\Song\Song');
    }

}
