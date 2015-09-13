<?php

namespace Sihae;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

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
     * Sets a given setting to the given value
     *
     * @param string $setting
     * @param string $value
     */
    public static function set($setting, $value)
    {
        $blogConfig = BlogConfig::where('setting', $setting)->first();

        if (!$blogConfig) {
            $blogConfig = new BlogConfig;
            $blogConfig->setting = $setting;
        }

        $blogConfig->value = $value;
        $blogConfig->save();
    }

    /**
     * Gets a given setting's value
     *
     * @param string $setting
     * @return string
     */
    private static function get($setting)
    {
        $row = BlogConfig::where('setting', $setting)->first();

        return $row ? $row->value : Config::get('blogconfig.' . $setting);
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
