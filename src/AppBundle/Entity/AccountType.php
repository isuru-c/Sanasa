<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountType
 *
 * @ORM\Table(name="account_type")
 * @ORM\Entity
 */
class AccountType
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_type", type="integer", nullable=false)
     */
    private $memberType;

    /**
     * @var integer
     *
     * @ORM\Column(name="account_type", type="integer", nullable=false)
     */
    private $accountType;

    /**
     * @var integer
     *
     * @ORM\Column(name="interest_type", type="integer", nullable=false)
     */
    private $interestType;

    /**
     * @var float
     *
     * @ORM\Column(name="interest", type="float", precision=4, scale=2, nullable=false)
     */
    private $interest;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set name
     *
     * @param string $name
     *
     * @return AccountType
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
     * Set memberType
     *
     * @param integer $memberType
     *
     * @return AccountType
     */
    public function setMemberType($memberType)
    {
        $this->memberType = $memberType;

        return $this;
    }

    /**
     * Get memberType
     *
     * @return integer
     */
    public function getMemberType()
    {
        return $this->memberType;
    }

    /**
     * Set accountType
     *
     * @param integer $accountType
     *
     * @return AccountType
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;

        return $this;
    }

    /**
     * Get accountType
     *
     * @return integer
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Set interestType
     *
     * @param integer $interestType
     *
     * @return AccountType
     */
    public function setInterestType($interestType)
    {
        $this->interestType = $interestType;

        return $this;
    }

    /**
     * Get interestType
     *
     * @return integer
     */
    public function getInterestType()
    {
        return $this->interestType;
    }

    /**
     * Set interest
     *
     * @param float $interest
     *
     * @return AccountType
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;

        return $this;
    }

    /**
     * Get interest
     *
     * @return float
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return AccountType
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
}
