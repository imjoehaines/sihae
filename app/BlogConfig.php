<?php

namespace Sihae;

use Illuminate\Database\Eloquent\Model;

class BlogConfig extends Model
{
    protected $table = 'blog_config';

    public $timestamps = false;

    public static function title()
    {
        return BlogConfig::findOrFail(1)->title;
    }
}
