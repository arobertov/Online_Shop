<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserAddress
 *
 * @ORM\Table(name="user_address")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserAddressRepository")
 */
class UserAddress
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
     * @ORM\Column(name="City", type="string", length=100)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="shipAddress", type="string", length=255)
     */
    private $shipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneNumber", type="string", length=255)
     */
    private $phoneNumber;

	/**
	 * @var User
	 *
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", mappedBy="address")
	 */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set city
     *
     * @param string $city
     *
     * @return UserAddress
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set shipAddress
     *
     * @param string $shipAddress
     *
     * @return UserAddress
     */
    public function setShipAddress($shipAddress)
    {
        $this->shipAddress = $shipAddress;

        return $this;
    }

    /**
     * Get shipAddress
     *
     * @return string
     */
    public function getShipAddress()
    {
        return $this->shipAddress;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return UserAddress
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return UserAddress
     */
    public function setUser( User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}
