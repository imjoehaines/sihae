<?php

namespace Sihae\Entities\Traits;

use DateTime;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_modified;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $date = new DateTime();

        $this->date_created = $date;
        $this->date_modified = $date;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->date_modified = new DateTime();
    }

    public function hasBeenModified() : bool
    {
        return $this->date_created != $this->date_modified;
    }

    public function getDateCreated() : Carbon
    {
        return Carbon::instance($this->date_created);
    }

    public function getDateModified() : Carbon
    {
        return Carbon::instance($this->date_modified);
    }
}
