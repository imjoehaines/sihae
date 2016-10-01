<?php

namespace Sihae;

use PDO;
use ArrayAccess;
use imjoehaines\Norman\Norman;
use function Stringy\create as s;

class Post extends Norman
{
    public $id;
    public $title;
    public $slug;
    public $body;
    public $date_created;

    protected $summary;

    public function __construct(PDO $db, array $properties = [])
    {
        parent::__construct($db, $properties);

        $this->summary = s($this->body)->safeTruncate(450, '…');
        $this->table = 'posts';
    }

    public function getSummary() : string
    {
        return $this->summary;
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
}
