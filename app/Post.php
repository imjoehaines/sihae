<?php

namespace Sihae;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    /**
     * @var string
     */
    protected $table = 'posts';

    public function dateCreated()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
    }

    public function timeSinceDateCreated()
    {
        return $this->dateCreated()->diffForHumans();
    }
}
