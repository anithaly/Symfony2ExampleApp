<?php

namespace Acme\LogEntryBundle\Entity\Interfaces;

interface CustomLogInterface
{
    /**
     * get Object name
     * @return $this
     */
    public function getObjectName();

    /**
     * set Object name
     * @return $name
     */
    public function setObjectName($name);
}
