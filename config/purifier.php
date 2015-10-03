<?php
/**
 * Ok, glad you are here
 * first we get a config instance, and set the settings
 * $config = HTMLPurifier_Config::createDefault();
 * $config->set('Core.Encoding', $this->config->get('purifier.encoding'));
 * $config->set('Cache.SerializerPath', $this->config->get('purifier.cachePath'));
 * if ( ! $this->config->get('purifier.finalize')) {
 *     $config->autoFinalize = false;
 * }
 * $config->loadArray($this->getConfig());
 *
 * You must NOT delete the default settings
 * anything in settings should be compacted with params that needed to instance HTMLPurifier_Config.
 *
 * @link http://htmlpurifier.org/live/configdoc/plain.html
 */

return [
    'encoding' => 'UTF-8',
    'finalize' => true,
    'cachePath' => storage_path('app/purifier'),
    'settings' => [
        'default' => [
            'HTML.Doctype' => 'XHTML 1.0 Strict',
            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,blockquote,table[summary],strong,em,a[href|title],ul,ol,dl,li,p,img[width|height|alt|src],pre,code[class],dd,dt,td[abbr],th[abbr]',
            'CSS.AllowedProperties' => '',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
        ],
        'test' => [
            'Attr.EnableID' => true
        ],
        'youtube' => [
            'HTML.SafeIframe' => 'true',
            'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%',
        ],
    ],
];
