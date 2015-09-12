<?php

namespace Sihae;

use Illuminate\Database\Eloquent\Model;

class BlogConfig extends Model
{
    /**
     * @var string
     */
    protected $table = 'blog_config';

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Gets a given setting's value
     *
     * @param string $setting
     * @return string
     */
    private static function get($setting)
    {
        return BlogConfig::where('setting', $setting)->firstOrFail()->value;
    }

    /**
     * Gets the title of the blog
     *
     * @return string
     */
    public static function title()
    {
        return BlogConfig::get('title');
    }
}
