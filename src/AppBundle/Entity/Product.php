<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ApiResource(
 *     collectionOperations={
 *         "product"={
 *             "method"="GET",
 *             "path"="/products",
 *             "normalization_context"={
 *                 "groups"={
 *                     "api_out_default",
 *                     "api_out_product"
 *                 }
 *             }
 *         },
 *         "incompatibleness"={
 *             "method"="GET",
 *             "path"="/incompatibilinesses",
 *             "normalization_context"={
 *                 "groups"={
 *                     "api_out_default",
 *                     "api_out_incompatibleness"
 *                 }
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get"={"method"="GET"}
 *     },
 *     attributes={
 *         "filters"={
 *             "product.gtin_search",
 *             "product.fulltext_search"
 *         },
 *         "normalization_context"={
 *             "groups"={
 *                 "api_out_default"
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity
 */
class Product
{
    const ZA_UNDEFINED = 0;
    const ZA_VEGAN = 1;
    const ZA_PROBABLY_VEGAN = 2;
    const ZA_VEGETARIAN = 3;
    const ZA_PROBABLY_VEGETARIAN = 4;

    const PACKAGINGLABELS_VEGAN = [
        "VEGAN_SOCIETY_VEGAN_LOGO",
    ];

    const DIETTYPEINFORMATIONS_VEGAN = [
        "VEGAN",
    ];

    const DIETTYPEINFORMATIONS_VEGETARIAN = [
        "VEGETARIAN",
    ];

    const ALLERGENTYPECODES_VEGETARIAN = [
        "AM",
        "AC",
        "ML",
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="gtin", type="bigint", nullable=false)
     * @Groups({"api_out_default", "api_out_product"})
     */
    private $gtin;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Groups({"api_out_product"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=16777215, nullable=false)
     * @Groups({"api_out_product"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="brandname", type="string", length=255, nullable=false)
     * @Groups({"api_out_product"})
     */
    private $brandname;

    /**
     * @var string
     *
     * @ORM\Column(name="brandowner", type="string", length=255, nullable=false)
     * @Groups({"api_out_product"})
     */
    private $brandowner;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     * @Groups({"api_out_product"})
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=false)
     * @Groups({"api_out_product"})
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="categorycode", type="integer", nullable=false)
     */
    private $categorycode;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="ingredients", type="text", length=16777215, nullable=false)
     * @Groups({"api_out_product"})
     */
    private $ingredients;

    /**
     * @var array
     */
    private $ingredientsList;

    /**
     * @var array
     *
     * @ORM\Column(name="nutrient", type="json_array", length=16777215, nullable=false)
     */
    private $nutrient;

    /**
     * @var array
     *
     * @ORM\Column(name="allergen", type="json_array", length=16777215, nullable=false)
     */
    private $allergen;

    /**
     * @var array
     *
     * @ORM\Column(name="allergentype", type="json_array", length=16777215, nullable=false)
     */
    private $allergentype;

    /**
     * @var array
     *
     * @ORM\Column(name="certification", type="json_array", length=16777215, nullable=false)
     */
    private $certification;

    /**
     * @var array
     *
     * @ORM\Column(name="additiveinformation", type="json_array", length=16777215, nullable=false)
     */
    private $additiveinformation;

    /**
     * @var array
     *
     * @ORM\Column(name="diettypeinformation", type="json_array", length=16777215, nullable=false)
     */
    private $diettypeinformation;

    /**
     * @var array
     *
     * @ORM\Column(name="packaginglabel", type="json_array", length=16777215, nullable=false)
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
     * Defines vegetarian / vegan type
     *
     * @var int
     * @Groups({"api_out_product", "api_out_incompatibleness"})
     */
    private $za = self::ZA_UNDEFINED;

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
     * @return array
     */
    public function getIngredientsList()
    {
        $ingredients = str_replace(array('Zutaten:', "\n"), '', $this->ingredients);
        return array_map('trim', explode(', ', $ingredients));
    }

    /**
     * @return array
     */
    public function getNutrient()
    {
        return $this->nutrient;
    }

    /**
     * @return array
     */
    public function getAllergen()
    {
        return $this->allergen;
    }

    /**
     * @return array
     */
    public function getAllergentype()
    {
        return $this->allergentype;
    }

    /**
     * @return array
     */
    public function getCertification()
    {
        return $this->certification;
    }

    /**
     * @return array
     */
    public function getAdditiveinformation()
    {
        return $this->additiveinformation;
    }

    /**
     * @return array
     */
    public function getDiettypeinformation()
    {
        return $this->diettypeinformation;
    }

    /**
     * @return array
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

    /**
     * @return int
     */
    public function getZa()
    {
        if (!empty(array_filter($this->getPackaginglabel(), function ($packaginglabel) {
            return in_array($packaginglabel, self::PACKAGINGLABELS_VEGAN);
        }))) {
            return self::ZA_VEGAN;
        }

        if (!empty($this->getDiettypeinformation()) && !empty($this->getDiettypeinformation()['dietTypeInformation'])) {
            if (!empty(array_filter($this->getDiettypeinformation()['dietTypeInformation'],
                function ($dietTypeInformation) {
                    return in_array($dietTypeInformation, self::DIETTYPEINFORMATIONS_VEGAN);
                }))
            ) {
                return self::ZA_VEGAN;
            }

            if (!empty(array_filter($this->getDiettypeinformation()['dietTypeInformation'],
                function ($dietTypeInformation) {
                    return in_array($dietTypeInformation, self::DIETTYPEINFORMATIONS_VEGETARIAN);
                }))
            ) {
                return self::ZA_VEGETARIAN;
            }
        }

        if (stripos($this->getName(), 'vegan') !== false || stripos($this->getDescription(), 'vegan') !== false) {
            return self::ZA_PROBABLY_VEGAN;
        }

        if (!empty($this->getAllergen()) && !empty($this->getAllergen()['allergen'])) {
            if (!empty(array_filter($this->getAllergen()['allergen'],
                function ($allergen) {
                    return !empty($allergen['allergenTypeCode']) && in_array($allergen['allergenTypeCode'], self::ALLERGENTYPECODES_VEGETARIAN);
                }))
            ) {
                return self::ZA_PROBABLY_VEGETARIAN;
            }
        }

        return self::ZA_UNDEFINED;
    }
}
