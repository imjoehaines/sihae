<?php

namespace Sihae\Providers;

use Cache;
use Sihae\BlogConfig;
use Illuminate\Support\Facades\Config;

class ConfigServiceProvider
{
    /**
     * Sets a given setting to the given value
     *
     * @param string $setting
     * @param string $value
     * @return boolean success
     */
    public static function set($setting, $value)
    {
        if (Cache::has($setting)) {
            Cache::forget($setting);
        }

        Cache::forever($setting, $value);

        $blogConfig = BlogConfig::where('setting', $setting)->first();

        if (!$blogConfig) {
            $blogConfig = new BlogConfig;
            $blogConfig->setting = $setting;
        }

        $blogConfig->value = $value;
        return $blogConfig->save();
    }

    /**
     * Gets a given setting's value, either from the database or, if it is not
     * there, from the defaults (@see config/blogconfig.php)
     *
     * @param string $setting
     * @return string
     */
    public static function get($setting)
    {
        if (Cache::has($setting)) {
            return Cache::get($setting);
        }

        $row = BlogConfig::where('setting', $setting)->first();

        return $row ? $row->value : Config::get('blogconfig.' . $setting);
    }
}
