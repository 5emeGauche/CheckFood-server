<?php

namespace Checkfood\ProductsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Checkfood\ProductsBundle\Entity\ProductsRepository")
 */
class Products
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
     * @ORM\Column(name="code", type="string", length=45)
     */
    private $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=45)
     */
    private $brand;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255)
     */
    private $imageUrl;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image_small_url", type="string", length=255)
     */
    private $imageSmallUrl;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getCode() {
        return $this->code;
    }

    public function getName() {
        return $this->name;
    }

    public function getBrand() {
        return $this->brand;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public function getImageSmallUrl() {
        return $this->imageSmallUrl;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setBrand($brand) {
        $this->brand = $brand;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    public function setImageSmallUrl($imageSmallUrl) {
        $this->imageSmallUrl = $imageSmallUrl;
    }


}
