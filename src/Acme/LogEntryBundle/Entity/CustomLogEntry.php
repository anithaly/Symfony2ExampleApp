<?php

namespace Acme\LogEntryBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\LogEntry;
// use Gedmo\Loggable\Document\MappedSuperclass\AbstractLogEntry;

/**
 * CustomLogEntry
 *
 * @ORM\Table(name="custom_log_entry")
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
     * Object name
     * @ORM\Column(name="objectName", type="string", length=20)
     */
    protected $objectName;

    /**
     * Is checked as read
     * @ORM\Column(type="boolean", name="is_checked", nullable=true)
     */
    protected $is_checked = false;

    /**
     * @var \App\Accounts\Bundle\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Acme\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
     */
    protected $user;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getObjectName()
    {
        return $this->objectName;
    }

    public function setObjectName($name)
    {
        $this->objectName = $name;
    }

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
