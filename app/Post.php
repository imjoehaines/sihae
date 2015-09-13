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

    /**
     * Gets the date a post was created
     *
     * @return Carbon
     */
    public function dateCreated()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
    }

    /**
     * Gets the time since the date a post was created - e.g. "5 days ago"
     *
     * @return string
     */
    public function timeSinceDateCreated()
    {
        return $this->dateCreated()->diffForHumans();
    }
}
