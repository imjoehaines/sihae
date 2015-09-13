<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

use Sihae\BlogConfig;
use Illuminate\Support\Facades\Config;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @var string
     */
    protected static $defaultBlogTitle = 'Sihae';

    /**
    * @BeforeSuite
    */
    public static function prepare()
    {
        self::setBlogTitleTo(Config::get('blogconfig.title'));
    }

    /**
    * @AfterScenario @database
    */
    public function cleanDB()
    {
        self::setBlogTitleTo(Config::get('blogconfig.title'));
    }

    /**
     * Sets the blog title to a given string
     *
     * @param string $title
     */
    protected static function setBlogTitleTo($title)
    {
        BlogConfig::set('title', $title);
    }

    /**
     * @Given my blog is called :blogTitle
     */
    public function myBlogIsCalled($blogTitle)
    {
        self::setBlogTitleTo($blogTitle);
    }
}
