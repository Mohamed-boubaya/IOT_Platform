<?php

namespace AppBundle\Entity\Cookie;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Category
 *
 * @ORM\Table(name="IOT_Projet_cookie")
 * @ORM\Entity("AppBundle\Entity\Cookie\CookieRepository")
 */
class Cookie
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
     * @ORM\Column(name="cookie", type="string", length=255)
     */
    private $cookie;

    /**
     * @var
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\eleve")
     */
    private $eleve;

    /**
     * @var Array
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cookie\SubmittedCookie", mappedBy="SubmittedCookie")
     */
    private $submittedCookies;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="tokens", type="integer")
     */
    private $tokens=5;

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
     * Set cookie
     *
     * @param string $cookie
     *
     * @return Cookie
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;

        return $this;
    }

    /**
     * Get cookie
     *
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Set tokens
     *
     * @param integer $tokens
     *
     * @return Cookie
     */
    public function setTokens($tokens)
    {
        $this->tokens = $tokens;

        return $this;
    }

    /**
     * Get tokens
     *
     * @return integer
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Set eleve
     *
     * @param \AppBundle\Entity\Eleve $eleve
     *
     * @return Cookie
     */
    public function setEleve(\AppBundle\Entity\Eleve $eleve = null)
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * Get Eleve
     *
     * @return \AppBundle\Entity\eleve
     */
    public function geteleve()
    {
        return $this->e7yt6r5eleve;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Cookie
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->submittedCookies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add submittedCooky
     *
     * @param \AppBundle\Entity\Cookie\SubmittedCookie $submittedCooky
     *
     * @return Cookie
     */
    public function addSubmittedCooky(\AppBundle\Entity\Cookie\SubmittedCookie $submittedCooky)
    {
        $this->submittedCookies[] = $submittedCooky;

        return $this;
    }

    /**
     * Remove submittedCooky
     *
     * @param \AppBundle\Entity\Cookie\SubmittedCookie $submittedCooky
     */
    public function removeSubmittedCooky(\AppBundle\Entity\Cookie\SubmittedCookie $submittedCooky)
    {
        $this->submittedCookies->removeElement($submittedCooky);
    }

    /**
     * Get submittedCookies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubmittedCookies()
    {
        return $this->submittedCookies;
    }
}
