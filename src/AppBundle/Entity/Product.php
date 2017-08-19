<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ApiResource(
 *     attributes={
 *         "filters"={
 *             "product.gtin_search"
 *         },
 *         "normalization_context"={
 *             "groups"={
 *                 "api_out"
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="gtin", type="bigint", nullable=false)
     * @Groups({"api_out"})
     */
    private $gtin;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Groups({"api_out"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="brandname", type="string", length=255, nullable=false)
     * @Groups({"api_out"})
     */
    private $brandname;

    /**
     * @var string
     *
     * @ORM\Column(name="brandowner", type="string", length=255, nullable=false)
     * @Groups({"api_out"})
     */
    private $brandowner;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     * @Groups({"api_out"})
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=false)
     * @Groups({"api_out"})
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="categorycode", type="integer", nullable=false)
     * @Groups({"api_out"})
     */
    private $categorycode;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     * @Groups({"api_out"})
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="ingredients", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $ingredients;

    /**
     * @var string
     *
     * @ORM\Column(name="nutrient", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $nutrient;

    /**
     * @var string
     *
     * @ORM\Column(name="allergen", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $allergen;

    /**
     * @var string
     *
     * @ORM\Column(name="allergentype", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $allergentype;

    /**
     * @var string
     *
     * @ORM\Column(name="certification", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $certification;

    /**
     * @var string
     *
     * @ORM\Column(name="additiveinformation", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $additiveinformation;

    /**
     * @var string
     *
     * @ORM\Column(name="diettypeinformation", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $diettypeinformation;

    /**
     * @var string
     *
     * @ORM\Column(name="packaginglabel", type="text", length=16777215, nullable=false)
     * @Groups({"api_out"})
     */
    private $packaginglabel;

    /**
     * @var string
     *
     * @ORM\Column(name="lastupdated", type="string", length=255, nullable=false)
     */
    private $lastupdated;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="checksum", type="integer", nullable=false)
     */
    private $checksum;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return int
     */
    public function getGtin()
    {
        return $this->gtin;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getBrandname()
    {
        return $this->brandname;
    }

    /**
     * @return string
     */
    public function getBrandowner()
    {
        return $this->brandowner;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getCategorycode()
    {
        return $this->categorycode;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @return string
     */
    public function getNutrient()
    {
        return $this->nutrient;
    }

    /**
     * @return string
     */
    public function getAllergen()
    {
        return $this->allergen;
    }

    /**
     * @return string
     */
    public function getAllergentype()
    {
        return $this->allergentype;
    }

    /**
     * @return string
     */
    public function getCertification()
    {
        return $this->certification;
    }

    /**
     * @return string
     */
    public function getAdditiveinformation()
    {
        return $this->additiveinformation;
    }

    /**
     * @return string
     */
    public function getDiettypeinformation()
    {
        return $this->diettypeinformation;
    }

    /**
     * @return string
     */
    public function getPackaginglabel()
    {
        return $this->packaginglabel;
    }

    /**
     * @return string
     */
    public function getLastupdated()
    {
        return $this->lastupdated;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
