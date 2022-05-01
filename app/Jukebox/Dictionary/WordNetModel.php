<?php

namespace App\Jukebox\Dictionary;

use App\Words\GlossaryNet;
use Illuminate\Database\Eloquent\Model;

class WordNetModel extends Model
{

    protected $connection = 'mysql3';

    protected $table = 'wn_synset';

   /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $synset_id;

    /**
     * @var integer
     */
    protected $w_num;

    /**
     * @var string
     */
    protected $ss_type;

    /**
     * The word.
     *
     * @var string
     */
    protected $word;

    /**
     *
     * @var integer
     */
    protected $sense_number;

    /**
     * @var integer
     */
    protected $tag_count;

    /**
     * Get the glossary associated with the word.
     */
    public function glossary()
    {
        return $this->hasOne(GlossaryNet::class, 'synset_id', 'synset_id');
    }

}
