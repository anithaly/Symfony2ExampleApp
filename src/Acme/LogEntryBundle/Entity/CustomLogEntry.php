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
     * @ORM\Column(type="array", name="before_data", nullable=true)
     */
    protected $before_data;

    public function setBeforeData($before_data)
    {
        $this->before_data = $before_data;

        return $this;
    }

    public function getBeforeData()
    {
        return $this->before_data;
    }

}
