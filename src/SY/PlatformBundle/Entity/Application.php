<?php

namespace SY\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table(name="application")
 * @ORM\Entity(repositoryClass="SY\PlatformBundle\Repository\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Application
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="SY\PlatformBundle\Entity\Advert", inversedBy="applications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;


    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @ORM\PrePersist()
     */
    public function increase()
    {
        $this->getAdvert()->increaseApplications();
    }

    /**
     * @ORM\PreRemove()
     */
    public function decrease()
    {
        $this->getAdvert()->decreaseApplications();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author.
     *
     * @param string $author
     *
     * @return Application
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Application
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Application
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set advert.
     *
     * @param \SY\PlatformBundle\Entity\Advert $advert
     *
     * @return Application
     */
    public function setAdvert(Advert $advert)
    {
        $this->advert = $advert;
    }

    /**
     * Get advert.
     *
     * @return \SY\PlatformBundle\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

}
