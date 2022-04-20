<?php

namespace App\Words;

use App\Words\WordNet;
use Illuminate\Database\Eloquent\Model;

class GlossaryNet extends Model
{

    protected $connection = 'mysql3';

    protected $table = 'wn_gloss';

   /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $synset_id;

    /**
     * @var string
     */
    protected $gloss;

    /**
     * Get the word the glosssary describes.
     */
    public function word()
    {
        return $this->belongsTo(WordNet::class);
    }

}
