<?php

namespace Acme\PublicationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Acme\LogEntryBundle\Entity\Interfaces\CustomLogInterface;

/**
 * Publication
 *
 * @ORM\Table(name="publications")
 * @ORM\Entity
 * @Gedmo\Loggable(logEntryClass="Acme\LogEntryBundle\Entity\CustomLogEntry")
 */
class Publication implements CustomLogInterface
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
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message = "Please enter a title")
     * @Assert\Length(
     *      min = "5",
     *      max = "255",
     *      minMessage = "Your title name must be at least {{ limit }} characters length",
     *      maxMessage = "Your title name cannot be longer than {{ limit }} characters length"
     * )
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message = "Please enter a content")
     */
    private $content;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="publications")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="publications")
     * @ORM\JoinTable(name="publications_tag")
     **/
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="publication")
     **/
    private $comments;

    private $objectName = "Publication";

    public function __construct() {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Publication
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Publication
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(Tag $tag)
    {
        $tag->addPublication($this); // synchronously updating inverse side
        $this->tags[] = $tag;
    }

    /**
     * Get comments
     *
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function getObjectName()
    {
        return $this->objectName;
    }

    public function setObjectName($name)
    {
        $this->objectName = $name;
    }
}
