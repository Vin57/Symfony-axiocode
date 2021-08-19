<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Axiocode\ApiBundle\Annotation\ExposeResource;
use Axiocode\ApiBundle\Annotation\ExposeRoute;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Product\Repository\ProductRepository")
 * @ExposeResource(
 *     fetchAll=@ExposeRoute(name="api_products", map={"id", "name", "category"}),
 *     fetchOne=@ExposeRoute(name="api_product", map={"id", "name", "category", "pictures"})
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $pictures;

    /**
     * @ORM\OneToMany(targetEntity=Opinion::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $opinions;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->opinions = new ArrayCollection();
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

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function getMainPicture() :?Picture
    {
        return $this->getPictures()->filter(function($p) {
            return $p->getIsMain();
        })->first() ?: null;
    }

    /**
     * @return int|null The average opinion rating, otherwise
     * null if there is no opinion.
     */
    public function getAverageOpinionRating() : ?int
    {
        if (!$count = intval($this->getOpinions()->count())) {
            return null;
        }
        $total = intval(array_reduce($this->getOpinions()->toArray(), function($carry, Opinion $o) {
             return $carry += $o->getRating();
        }));
        return ($total / $count)?: null;
    }

    /**
     * Get opinion for given {@see $user} on current {@see Product} entity.
     * @param User $user
     * @return Opinion|null Opinion for given {@see $user}, otherwise null.
     */
    public function getUserOpinion(User $user): ?Opinion {
        foreach ($this->getOpinions() as $opinion) {
            if ($opinion->getUser()->getId() === $user->getId()) {
                return $opinion;
            }
        }
        return null;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setProduct($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProduct() === $this) {
                $picture->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Opinion[]
     */
    public function getOpinions(): Collection
    {
        return $this->opinions;
    }

    public function addOpinion(Opinion $opinion): self
    {
        if (!$this->opinions->contains($opinion)) {
            $this->opinions[] = $opinion;
            $opinion->setProduct($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): self
    {
        if ($this->opinions->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getProduct() === $this) {
                $opinion->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
