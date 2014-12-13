<?php

namespace Acme\LogEntryBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Loggable\Entity\LogEntry;

/**
 * CustomLogEntry
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CustomLogEntry extends LogEntry
{

    /**
     * Serialized array of old data
     * @ORM\Column(type="array", name="before_data", nullable=true)
     */
    protected $before_data;

    /**
     * @ORM\Column(type="integer", name="user_id")
     */
    protected $user_id;

    /**
     * Is checked as read
     * @ORM\Column(type="boolean", name="is_checked")
     */
    protected $is_checked = false;

    public function setBeforeData($before_data)
    {
        $this->before_data = $before_data;

        return $this;
    }

    public function getBeforeData()
    {
        return $this->before_data;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setIsChecked($is_checked)
    {
        $this->is_checked = $is_checked;

        return $this;
    }

    public function getIsChecked()
    {
        return $this->is_checked;
    }

}
