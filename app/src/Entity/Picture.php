<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unq_mainpicture_product", fields={"product", "is_main"})})
 * @UniqueEntity(fields={"product", "is_main"})
 */
class Picture extends Document
{
    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $is_main = null;

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getIsMain(): ?bool
    {
        return $this->is_main;
    }

    public function setIsMain(bool $is_main): self
    {
        $this->is_main = $is_main;

        return $this;
    }
}
