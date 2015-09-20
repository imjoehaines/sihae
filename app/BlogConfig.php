<?php

namespace Sihae;

use Illuminate\Database\Eloquent\Model;
use Sihae\Providers\ConfigServiceProvider;

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
     * Sets a given setting to the given value - proxy for ConfigServiceProvider::set
     *
     * @param string $setting
     * @param string $value
     * @return boolean success
     */
    public static function set($setting, $value)
    {
        return ConfigServiceProvider::set($setting, $value);
    }

    /**
     * Gets the title of the blog
     *
     * @return string
     */
    public static function title()
    {
        return ConfigServiceProvider::get('title');
    }

    /**
     * Gets the number of posts per page
     *
     * @return string
     */
    public static function postsPerPage()
    {
        return ConfigServiceProvider::get('postsPerPage');
    }
}
