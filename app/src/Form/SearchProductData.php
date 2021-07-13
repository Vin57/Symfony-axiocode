<?php


namespace App\Form;


use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class SearchProductData
{
    /**
     * @var string|null
     * @Assert\NotBlank(allowNull=true)
     */
    private ?string $name = null;

    /**
     * @var Category|null
     * @Assert\Type(type="App\Entity\Category")
     */
    private ?Category $category = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
}