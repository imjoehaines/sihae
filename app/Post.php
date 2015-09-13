<?php

namespace Sihae;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Post extends Model implements SluggableInterface
{
    use SluggableTrait;

    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

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
