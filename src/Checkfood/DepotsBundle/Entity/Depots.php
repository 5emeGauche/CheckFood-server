<?php

namespace Checkfood\DepotsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Depots
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Checkfood\DepotsBundle\Entity\DepotsRepository")
 */
class Depots {

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
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=32)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=32)
     */
    private $latitude;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="opening_time", type="time", nullable=true)
     */
    private $openingTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closing_time", type="time", nullable=true)
     */
    private $closingTime;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Depots
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Depots
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Depots
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Depots
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set openingTime
     *
     * @param \DateTime $openingTime
     * @return Depots
     */
    public function setOpeningTime($openingTime) {
        $this->openingTime = $openingTime;

        return $this;
    }

    /**
     * Get openingTime
     *
     * @return \DateTime 
     */
    public function getOpeningTime() {
        $openingTime = isset($this->openingTime) ? date("H:i", $this->openingTime->getTimestamp()) : null;
        return $openingTime;
    }

    /**
     * Set closingTime
     *
     * @param \DateTime $closingTime
     * @return Depots
     */
    public function setClosingTime($closingTime) {
        $this->closingTime = $closingTime;

        return $this;
    }

    /**
     * Get closingTime
     *
     * @return \DateTime 
     */
    public function getClosingTime() {
        $closingTime = isset($this->closingTime) ? date("H:i", $this->closingTime->getTimestamp()) : null;
        return $closingTime;
    }

}
