<?php

namespace Sihae\Entities;

use function Stringy\create as s;

/**
 * This is a "pretend" entity so we can use either a Post or Page interchangably
 * in the "editor" template
 */
class Page
{
    protected $title;
    protected $slug;
    protected $body;

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

    public function setTitle(string $title) : Page
    {
        $this->title = $title;

        if (!$this->slug) {
            $this->slug = (string) s($title)->slugify();
        }

        return $this;
    }

    public function setBody(string $body) : Page
    {
        $this->body = $body;

        return $this;
    }
}
