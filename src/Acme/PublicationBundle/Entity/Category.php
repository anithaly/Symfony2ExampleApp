<?php

namespace Acme\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Acme\LogEntryBundle\Entity\Interfaces\CustomLogInterface;

/**
 * Category
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity
 * @Gedmo\Loggable(logEntryClass="Acme\LogEntryBundle\Entity\CustomLogEntry")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Category implements CustomLogInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @JMS\Exclude
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @JMS\Exclude
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="category", cascade={"persist", "remove"})
     **/
    private $publications;

    private $objectName = "Category";

    public function __construct() {
        $this->publications = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getPublications()
    {
        return $this->publications;
    }

    public function setPublications($publications)
    {
        $this->publications = $publications;

        return $this;
    }

    public function getObjectName()
    {
        return $this->objectName;
    }

    public function setObjectName($name)
    {
        $this->objectName = $name;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}
