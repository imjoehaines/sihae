<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

use Sihae\BlogConfig;

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
        $blogConfig = BlogConfig::find(1);
        $blogConfig->title = 'Sihae';
        $blogConfig->save();
    }

    /**
    * @AfterScenario @database
    */
    public function cleanDB()
    {
        $blogConfig = BlogConfig::find(1);
        $blogConfig->title = 'Sihae';
        $blogConfig->save();
    }

    /**
     * @Given my blog is called :blogTitle
     */
    public function myBlogIsCalled($blogTitle)
    {
        $blogConfig = BlogConfig::find(1);
        $blogConfig->title = $blogTitle;
        $blogConfig->save();
    }
}
