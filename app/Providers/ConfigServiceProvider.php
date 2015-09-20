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
        $cachedSetting = self::cacheNameFor($setting);

        if (Cache::has($cachedSetting)) {
            Cache::forget($cachedSetting);
        }

        Cache::forever($cachedSetting, $value);

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
        $cachedSetting = self::cacheNameFor($setting);

        if (Cache::has($cachedSetting)) {
            return Cache::get($cachedSetting);
        }

        $row = BlogConfig::select('value')->where('setting', $setting)->first();

        if ($row) {
            $value = $row->value;
        } else {
            $value = Config::get('blogconfig.' . $setting);
        }

        Cache::forever($cachedSetting, $value);

        return $value;
    }

    /**
     * Gets the name to use when caching a given setting
     *
     * @param string $setting
     * @return string
     */
    protected static function cacheNameFor($setting)
    {
        return __CLASS__ . '::' . $setting;
    }
}
