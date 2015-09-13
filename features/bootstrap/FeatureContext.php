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
    * @BeforeSuite
    */
    public static function prepare()
    {
        self::cleanDB();
    }

    /**
    * @AfterScenario @database
    */
    public static function cleanDB()
    {
        BlogConfig::truncate();
    }

    /**
     * @Given my blog is called :blogTitle
     */
    public function myBlogIsCalled($blogTitle)
    {
        BlogConfig::set('title', $blogTitle);
    }
}
