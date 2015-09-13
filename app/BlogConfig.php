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
     * Gets a given setting's value - proxy for ConfigServiceProvider::get
     *
     * @param string $setting
     * @return string
     */
    private static function get($setting)
    {
        return ConfigServiceProvider::get($setting);
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
