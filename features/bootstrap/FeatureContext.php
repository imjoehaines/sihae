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

        switch (getenv('DB_DRIVER')) {
            case 'pdo_mysql':
                $connection->query('SET foreign_key_checks = 0');

                $tables = $connection->executeQuery(
                    'SELECT table_name
                     FROM information_schema.tables
                     WHERE table_schema = :db_name',
                    ['db_name' => getenv('DB_NAME')]
                )->fetchAll();

                foreach ($tables as $table) {
                    $connection->query('TRUNCATE TABLE ' . $table['table_name']);
                }

                $connection->query('SET foreign_key_checks = 1');
                break;

            case 'pdo_sqlite':
                $connection->query('PRAGMA foreign_keys = OFF');

                $tables = $connection->query('SELECT name FROM sqlite_master WHERE type = "table";')->fetchAll();

                foreach ($tables as $table) {
                    $connection->query('DELETE FROM ' . $table['name']);
                }

                $connection->query('PRAGMA foreign_keys = ON');
                break;

            default:
                throw new PendingException();
        }
    }

    /**
     * @BeforeScenario @login
     */
    public function login()
    {
        $this->createTestUser();
        $this->loginTestUser();
    }

    /**
     * @BeforeScenario @loginAdmin
     */
    public function loginAdmin()
    {
        $this->createTestAdmin();
        $this->loginTestUser();
    }

    public function createTestAdmin()
    {
        $user = new User;
        $user->setUsername('testing');
        $user->setPassword('testing');
        $user->setIsAdmin(true);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    protected function loginTestUser()
    {
        $this->visit('/login');
        $this->fillField('username', 'testing');
        $this->fillField('password', 'testing');
        $this->pressButton('Login');
    }

    /**
     * Adds a test user to the database
     *
     * @BeforeScenario @createUser
     */
    public function createTestUser()
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
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'testing']);

        foreach ($posts as $content) {
            $post = new Post;

            $post->setTitle($content['title']);
            $post->setBody($content['body']);
            $post->setUser($user);

            $this->getEntityManager()->persist($post);
            $this->getEntityManager()->flush();

            if (isset($content['date_created'])) {
                $this->getEntityManager()->getConnection()->executeUpdate(
                    'UPDATE post
                     SET date_created = :date_created
                     WHERE title = :title',
                    [':date_created' => $content['date_created'], ':title' => $content['title']]
                );
            }
        }
    }
}
