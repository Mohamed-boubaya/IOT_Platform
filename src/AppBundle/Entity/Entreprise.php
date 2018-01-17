<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Entreprise
 *
 * @ORM\Table(name="IOT_Entreprise")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EntrepriseRepository")
 *
 */
class Entreprise
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"name"}, updatable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


   /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

     /**
     * @var integer
     *
     * @ORM\Column(name="phoneNum", type="integer")
     */
    private $phoneNum;
  
    
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
     * @ORM\Column(name="applicationDomaine", type="text")
     */
    private $applicationDomaine;


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
     * @param integer $phoneNum
     *
     * @return Entreprise
     */
    public function setPhoneNum($phoneNum)
    {
        $this->phoneNum = $phoneNum;

        return $this;
    }

  /**
     * Get phoneNum
     *
     * @return integer
     */
    public function getPhoneNum()
    {
        return $this->phoneNum;
    }
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Enreprise
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
     * Set description
     *
     * @param string $description
     *
     * @return Enreprise
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set email
     *
     * @param string $email
     *
     * @return Enreprise
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

   
/**
     * Set applicationDomaine
     *
     * @param string $applicationDomaine
     *
     * @return Enreprise
     */
    public function setApplicationDomaine($applicationDomaine)
    {
        $this->applicationDomaine = $applicationDomaine;

        return $this;
    }

    /**
     * Get applicationDomaine
     *
     * @return string
     */
    public function getApplicationDomaine()
    {
        return $this->applicationDomaine;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Enreprise
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Enreprise
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


}
