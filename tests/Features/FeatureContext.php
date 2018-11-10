<?php declare(strict_types=1);

namespace Sihae\Tests\Features;

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
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @BeforeSuite
     * @return void
     */
    public static function prepare() : void
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../../');
        $dotenv->load();

        (new static)->cleanDb();
    }

    /**
     * @BeforeScenario @database
     * @throws PendingException when the database driver isn't supported
     * @return void
     */
    public function cleanDb() : void
    {
        $connection = $this->getEntityManager()->getConnection();
        $schemaManager = $connection->getSchemaManager();

        $tables = $schemaManager->listTables();

        foreach ($tables as $table) {
            foreach ($table->getForeignKeys() as $foreignKey) {
                $table->removeForeignKey($foreignKey->getName());
            }

            $connection->exec('DELETE FROM ' . $connection->quoteIdentifier($table->getName()));
        }
    }

    /**
     * @BeforeScenario @login
     * @return void
     */
    public function login() : void
    {
        $this->createTestUser();
        $this->loginTestUser();
    }

    /**
     * @BeforeScenario @loginAdmin
     * @return void
     */
    public function loginAdmin() : void
    {
        $this->createTestAdmin();
        $this->loginTestUser();
    }

    /**
     * @return void
     */
    public function createTestAdmin() : void
    {
        $user = new User(
            'testing',
            'testing'
        );

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $this->getEntityManager()->getConnection()->executeUpdate(
            'UPDATE user
             SET is_admin = 1
             WHERE username = "testing"'
        );
    }

    /**
     * @return void
     */
    protected function loginTestUser() : void
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
     * @return void
     */
    public function createTestUser() : void
    {
        $user = new User('testing', 'testing');

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Get an instance of Doctrine's EntityManager
     *
     * @return EntityManager
     */
    protected function getEntityManager() : EntityManager
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
     * @return void
     */
    public function iAmLoggedIn() : void
    {
        $this->login();
    }

    /**
     * @Given there is a post:
     * @return void
     */
    public function thereIsAPost(TableNode $posts) : void
    {
        $this->thereAreSomePosts($posts);
    }

    /**
     * @Given there are some posts:
     * @return void
     */
    public function thereAreSomePosts(TableNode $posts) : void
    {
        $posts = $posts->getHash();
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy([]);

        if (!$user) {
            throw new \RuntimeException('Expected at least one user in the database');
        }

        foreach ($posts as $content) {
            $post = new Post(
                $content['title'],
                $content['body'],
                $user
            );

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
