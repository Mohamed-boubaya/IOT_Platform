<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Participation
 *
 * @ORM\Table(name="IOT_participation")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ParticipationRepository")
 */
class Participation
{
    /**
     * @var
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Promo", inversedBy="participations")
     */
    private $promo;

    /**
     * @var
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Eleve", inversedBy="participations")
     * @Gedmo\Blameable(on="create")
     */
    private $eleve;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isValidated", type="boolean")
     */
    private $isValidated;

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
     * @var integer
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score=0;


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
     * Set isValidated
     *
     * @param boolean $isValidated
     *
     * @return Participation
     */
    public function setIsValidated($isValidated)
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * Get isValidated
     *
     * @return boolean
     */
    public function getIsValidated()
    {
        return $this->isValidated;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Participation
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
     * Set score
     *
     * @param integer $score
     *
     * @return Participation
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Participation
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
     * Set Promo
     *
     * @param \AppBundle\Entity\Promo $promo
     *
     * @return Participation
     */
    public function setPromo(\AppBundle\Entity\Promo $promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get Promo
     *
     * @return \AppBundle\Entity\Promo
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set eleve
     *
     * @param \AppBundle\Entity\Eleve $eleve
     *
     * @return Participation
     */
    public function setEleve(\AppBundle\Entity\Eleve $eleve)
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * Get eleve
     *
     * @return \AppBundle\Entity\eleve
     */
    public function getEleve()
    {
        return $this->eleve;
    }
}
