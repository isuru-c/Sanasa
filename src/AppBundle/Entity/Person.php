<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity
 */
class Person
{
    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=200, nullable=false)
     */
    private $fullName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starting_date", type="date", nullable=false)
     */
    private $startingDate;

    /**
     * @var string
     *
     * @ORM\Column(name="nic_number", type="string", length=10)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nicNumber;



    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Person
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set startingDate
     *
     * @param \DateTime $startingDate
     *
     * @return Person
     */
    public function setStartingDate($startingDate)
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    /**
     * Get startingDate
     *
     * @return \DateTime
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * Set nicNumber
     *
     * @param string $nicNumber
     *
     * @return Person
     */
    public function setNicNumber($nicNumber)
    {
        $this->nicNumber = $nicNumber;

        return $this;
    }

    /**
     * Get nicNumber
     *
     * @return string
     */
    public function getNicNumber()
    {
        return $this->nicNumber;
    }
}
