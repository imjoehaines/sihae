<?php declare(strict_types=1);

namespace Sihae\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161028221622 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema) : void
    {
        $postTable = $schema->createTable('post');
        $postTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $postTable->addColumn('user_id', 'integer');
        $postTable->addColumn('title', 'string', ['length' => 100]);
        $postTable->addColumn('slug', 'string');
        $postTable->addColumn('body', 'text');
        $postTable->addColumn('is_page', 'boolean');
        $postTable->addColumn('date_created', 'datetime');
        $postTable->addColumn('date_modified', 'datetime');
        $postTable->setPrimaryKey(['id']);
        $postTable->addUniqueIndex(['slug']);

        $userTable = $schema->createTable('user');
        $userTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $userTable->addColumn('username', 'string', ['length' => 50]);
        $userTable->addColumn('password', 'string');
        $userTable->addColumn('token', 'string', ['length' => 256]);
        $userTable->addColumn('is_admin', 'boolean');
        $userTable->addColumn('date_created', 'datetime');
        $userTable->addColumn('date_modified', 'datetime');
        $userTable->setPrimaryKey(['id']);
        $userTable->addUniqueIndex(['username']);

        $tagTable = $schema->createTable('tag');
        $tagTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $tagTable->addColumn('name', 'string', ['length' => 50]);
        $tagTable->addColumn('slug', 'string');
        $tagTable->addColumn('date_created', 'datetime');
        $tagTable->addColumn('date_modified', 'datetime');
        $tagTable->setPrimaryKey(['id']);
        $tagTable->addUniqueIndex(['slug']);

        $postTagTable = $schema->createTable('post_tag');
        $postTagTable->addColumn('post_id', 'integer');
        $postTagTable->addColumn('tag_id', 'integer');
        $postTagTable->setPrimaryKey(['post_id', 'tag_id']);

        $postTable->addForeignKeyConstraint($userTable, ['user_id'], ['id']);
        $postTagTable->addForeignKeyConstraint($postTable, ['post_id'], ['id']);
        $postTagTable->addForeignKeyConstraint($tagTable, ['tag_id'], ['id']);
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema) : void
    {
        $schema->dropTable('post_tag');
        $schema->dropTable('tag');
        $schema->dropTable('post');
        $schema->dropTable('user');
    }
}
