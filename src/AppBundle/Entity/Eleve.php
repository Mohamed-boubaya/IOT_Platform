<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EleveRepository")
 * @ORM\Table(name="IOT_eleve")
 */
class Eleve extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"username"}, updatable=false)
     */
    private $slug;

   

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $organisation;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $country;





    

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Participation", mappedBy="eleve")
     */
    private $participations;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;



    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

     /**
     * @var
     * @ORM\Column(name="competitions", type="array", nullable=TRUE)
     */
    private $competitions;

    public function __construct()
    {
        parent::__construct();
        $this->competitions = new ArrayCollection();
       
    }



    /**
     * @ORM\Column(type="string", nullable=true)
     */

    private $fname;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $linkdin;



    

    /**
     * Set organisation
     *
     * @param string $organisation
     *
     * @return Eleve
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    /**
     * Get organisation
     *
     * @return string
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Eleve
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Eleve
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Eleve
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    
    

    /**
     * Add participation
     *
     * @param \AppBundle\Entity\Participation $participation
     *
     * @return Eleve
     */
    public function addParticipation(\AppBundle\Entity\Participation $participation)
    {
        $this->participations[] = $participation;

        return $this;
    }

    /**
     * Remove participation
     *
     * @param \AppBundle\Entity\Participation $participation
     */
    public function removeParticipation(\AppBundle\Entity\Participation $participation)
    {
        $this->participations->removeElement($participation);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Eleve
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get country
     *
     * @return boolean
     */
    public function isCondidate()
    {
        return (sizeof($this->roles)==0);
    }

    /**
     * Set Linkdin
     *
     * @param string $Linkdin
     *
     * @return Eleve
     */
    public function setLinkdin($linkdin)
    {
        $this->linkdin = $linkdin;

        return $this;
    }

    /**
     * Get Linkdin
     *
     * @return string
     */
    public function getLinkdin()
    {
        return $this->linkdin;
    }

 /**
     * Set name
     *
     * @param string $name
     *
     * @return Eleve
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


     /**
     * Set fname
     *
     * @param string $fname
     *
     * @return Eleve
     */
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    
   }
    /**
     * Set competitions
     *
     * @param array $competitions
     *
     * @return eleve
     */
    public function setCompetitions($competitions)
    {
        $this->competitions = $competitions;

        return $this;
    }

    /**
     * Get competitions
     *
     * @return array
     */

    public function getCompetitions()
    {
        return $this->competitions;
    }


}
