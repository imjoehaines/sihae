<?php

use Sihae\Entities\User;
use Sihae\Entities\Post;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements
    Context,
    SnippetAcceptingContext
{
    protected $entityManager;

    /**
     * @BeforeSuite
     */
    public static function prepare()
    {
        (new static)->cleanDb();
    }

    /**
     * @BeforeScenario @database
     */
    public function cleanDb()
    {
        $connection = $this->getEntityManager()->getConnection();

        $connection->query('PRAGMA foreign_keys = OFF');

        $tables = $connection->query('SELECT name FROM sqlite_master WHERE type = "table";')->fetchAll();

        foreach ($tables as $table) {
            $connection->query('DELETE FROM ' . $table['name']);
        }

        $connection->query('PRAGMA foreign_keys = ON');
    }

    /**
     * @BeforeScenario @login
     */
    public function login()
    {
        $this->createTestUser();

        $this->visit('/login');
        $this->fillField('username', 'testing');
        $this->fillField('password', 'testing');
        $this->pressButton('Login');
    }

    /**
     * Adds a test user to the database
     */
    protected function createTestUser()
    {
        $user = new User;
        $user->setUsername('testing');
        $user->setPassword('testing');

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    protected function getEntityManager()
    {
        if (!isset($this->entityManager)) {
            $settings = require __DIR__ . '/../../config/settings.php';

            $config = Setup::createAnnotationMetadataConfiguration(
                $settings['settings']['doctrine']['entity_path'],
                $settings['settings']['doctrine']['auto_generate_proxies'],
                $settings['settings']['doctrine']['proxy_dir'],
                $settings['settings']['doctrine']['cache'],
                false
            );

            $this->entityManager = EntityManager::create($settings['settings']['doctrine']['connection'], $config);
        }

        return $this->entityManager;
    }

    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $this->login();
    }

    /**
     * @Given there is a post:
     */
    public function thereIsAPost(TableNode $posts)
    {
        $this->thereAreSomePosts($posts);
    }

    /**
     * @Given there are some posts:
     */
    public function thereAreSomePosts(TableNode $posts)
    {
        $posts = $posts->getHash();

        foreach ($posts as $content) {
            $post = new Post;

            $post->setTitle($content['title']);
            $post->setBody($content['body']);

            $this->getEntityManager()->persist($post);
        }

        $this->getEntityManager()->flush();
    }
}
