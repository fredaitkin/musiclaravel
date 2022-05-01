<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Log;
use Redirect;
use Storage;

class UtilitiesRestController extends Controller
{

    /**
     * The media directory
     *
     * @var string
     */
    private $media_directory;

    /**
     * The file system root
     *
     * @var string
     */
    private $partition_root;

     /**
     * Constructor
     */
    public function __construct()
    {
        $this->media_directory = Redis::get('media_directory');
        $this->partition_root = config('filesystems.disks')[config('filesystems.partition')]['root'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('utilities', ['media_directory' => $this->media_directory]);
    }

}
