<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unq_name", fields={"name"})})
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="childrens")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent")
     */
    private $childrens;

    public function __construct()
    {
        $this->childrens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @param array|null $parents
     * @return Category[]
     */
    public function getParents(?array $parents = null) : array {
        if ($parent = $this->getParent()) {
            $parents[] = $this->getParent();
            $parents = $parent->getParents($parents);
        }
        return $parents;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function addChildren(self $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens[] = $children;
            $children->setParent($this);
        }

        return $this;
    }

    public function removeChildren(self $children): self
    {
        if ($this->childrens->removeElement($children)) {
            // set the owning side to null (unless already changed)
            if ($children->getParent() === $this) {
                $children->setParent(null);
            }
        }

        return $this;
    }
}
