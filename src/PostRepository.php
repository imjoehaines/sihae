<?php

namespace Sihae;

use PDO;
use Exception;

class PostRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(array $parameters = []) : array
    {
        $query = 'SELECT * FROM posts ORDER BY date_created DESC;';

        $statement = $this->db->prepare($query);
        $statement->execute();

        return array_map(function (array $post) : Post {
            return new Post($this->db, $post);
        }, $statement->fetchAll());
    }

    public function findBySlug(string $slug) : Post
    {
        $query = 'SELECT * FROM posts WHERE slug = :slug;';

        $statement = $this->db->prepare($query);
        $statement->execute(['slug' => $slug]);

        $result = $statement->fetch();

        if (empty($result)) {
            throw new PostNotFoundException('No post found with slug ' . $slug);
        }

        return new Post($this->db, $result);
    }
}
