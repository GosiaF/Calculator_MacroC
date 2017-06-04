<?php

namespace MCalculatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient")
 * @ORM\Entity(repositoryClass="MCalculatorBundle\Repository\IngredientRepository")
 */
class Ingredient
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
     * @ORM\Column(name="name", type="string", length=45, unique=true)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="kcal", type="float")
     */
    private $kcal;

    /**
     * @var float
     *
     * @ORM\Column(name="fat", type="float")
     */
    private $fat;

    /**
     * @var float
     *
     * @ORM\Column(name="carbohydrates", type="float")
     */
    private $carbohydrates;

    /**
     * @var float
     *
     * @ORM\Column(name="protein", type="float")
     */
    private $protein;


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
     * @param string $name
     * @return Ingredient
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
     * Set kcal
     *
     * @param float $kcal
     * @return Ingredient
     */
    public function setKcal($kcal)
    {
        $this->kcal = $kcal;

        return $this;
    }

    /**
     * Get kcal
     *
     * @return float 
     */
    public function getKcal()
    {
        return $this->kcal;
    }

    /**
     * Set fat
     *
     * @param float $fat
     * @return Ingredient
     */
    public function setFat($fat)
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * Get fat
     *
     * @return float 
     */
    public function getFat()
    {
        return $this->fat;
    }

    /**
     * Set carbohydrates
     *
     * @param float $carbohydrates
     * @return Ingredient
     */
    public function setCarbohydrates($carbohydrates)
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    /**
     * Get carbohydrates
     *
     * @return float 
     */
    public function getCarbohydrates()
    {
        return $this->carbohydrates;
    }

    /**
     * Set protein
     *
     * @param float $protein
     * @return Ingredient
     */
    public function setProtein($protein)
    {
        $this->protein = $protein;

        return $this;
    }

    /**
     * Get protein
     *
     * @return float 
     */
    public function getProtein()
    {
        return $this->protein;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
