<?php

namespace Sihae\Entities\Traits;

use DateTime;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

/**
 * A trait to add similar functionality to Eloquent's "timestamps".
 *
 * The date_created and date_modified properties will be added to the entity,
 * along with matching getters. Doctrine lifecycle callbacks are used to
 * generate and update these, so a class using this trait *must* add a
 * "@ORM\HasLifecycleCallbacks" annotation.
 *
 * There is also a convenience method "hasBeenModified" to quickly check if
 * any entity using this traite has ever been modified (i.e. the date_modified
 * is not the same as the date_created).
 */
trait Timestamps
{
    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $date_created;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $date_modified;

    /**
     * Before persisting this entity for the first time, set both date_created
     * and date_modified to the current date & time
     *
     * @ORM\PrePersist
     * @return void
     */
    public function onPrePersist()
    {
        $date = new DateTime();

        $this->date_created = $date;
        $this->date_modified = $date;
    }

    /**
     * Before updating this entity, update date_modified to the current date & time
     *
     * @ORM\PreUpdate
     * @return void
     */
    public function onPreUpdate()
    {
        $this->date_modified = new DateTime();
    }

    /**
     * Check if an entity has ever been modified
     *
     * @return boolean
     */
    public function hasBeenModified() : bool
    {
        return $this->date_created != $this->date_modified;
    }

    /**
     * Get the date this entity was created
     *
     * @return Carbon
     */
    public function getDateCreated() : Carbon
    {
        return Carbon::instance($this->date_created);
    }

    /**
     * Get the date this entity was last modified
     *
     * @return Carbon
     */
    public function getDateModified() : Carbon
    {
        return Carbon::instance($this->date_modified);
    }
}
