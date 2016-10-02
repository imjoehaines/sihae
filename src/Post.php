<?php

namespace Sihae;

use PDO;
use ArrayAccess;
use imjoehaines\Norman\Norman;
use function Stringy\create as s;

class Post extends Norman
{
    protected $id;
    protected $title;
    protected $slug;
    protected $body;
    protected $date_created;

    protected $columns = ['id', 'title', 'slug', 'body', 'date_created'];

    protected $table = 'posts';

    public function getSummary() : string
    {
        return s($this->body)->safeTruncate(450, '…');
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function getBody() : string
    {
        return $this->body;
    }

    public function getDateCreated() : string
    {
        return $this->date_created;
    }

    public function setTitle(string $title) : Post
    {
        $this->title = $title;

        return $this;
    }

    public function setBody(string $body) : Post
    {
        $this->body = $body;

        return $this;
    }
}
