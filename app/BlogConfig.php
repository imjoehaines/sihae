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
     * Checks if a setting is valid
     *
     * @param string $setting
     * @return boolean
     */
    protected static function isValid($setting)
    {
        return in_array($setting, self::validSettings());
    }

    /**
     * Gets all valid settings
     *
     * @return array
     */
    protected static function validSettings()
    {
        return array_keys(\Config::get('blogconfig'));
    }

    /**
     * Sets a given setting to the given value - proxy for ConfigServiceProvider::set
     *
     * @param string $setting
     * @param string $value
     * @return boolean success
     */
    public static function set($setting, $value)
    {
        if (self::isValid($setting)) {
            return ConfigServiceProvider::set($setting, $value);
        }

        return false;
    }

    /**
     * Sets all given settings to given values
     *
     * @param array $settings
     */
    public static function setAll(array $settings)
    {
        foreach ($settings as $setting => $value) {
            self::set($setting, $value);
        }
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

    /**
     * Gets whether to show a login link
     *
     * @return string
     */
    public static function showLoginLink()
    {
        return ConfigServiceProvider::get('showLoginLink');
    }

    /**
     * Gets the blog summary
     *
     * @return string
     */
    public static function summary()
    {
        return ConfigServiceProvider::get('summary');
    }
}
