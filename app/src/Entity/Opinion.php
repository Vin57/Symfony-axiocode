<?php

namespace App\Entity;

use Axiocode\ApiBundle\Annotation\ExposeRoute;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Axiocode\ApiBundle\Annotation\ExposeResource;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Opinion\Repository\OpinionRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unq_user_product_rating", fields={"user", "product"})})
 * @UniqueEntity(
 *     fields={"user", "product"},
 *     message="dupplicate.user.product.rating"
 * )
 * @ExposeResource(
 *     createOne=@ExposeRoute(name="api_opinion_create", input="comment,rating,product", isGranted="ROLE_USER"),
 *     updateOne=@ExposeRoute(name="api_opinion_update", input="comment,rating", isGranted="ROLE_USER"),
 *     deleteOne=@ExposeRoute(name="api_opinion_delete", isGranted="ROLE_USER")
 * )
 */
class Opinion
{
    const RATING_MAX_LENGTH = 1;
    const RATING_MIN_VALUE = 1;
    const RATING_MAX_VALUE = 5;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $comment = null;

    /**
     * @ORM\Column(type="integer", length=Opinion::RATING_MAX_LENGTH)
     * @Assert\GreaterThanOrEqual(value=Opinion::RATING_MIN_VALUE)
     * @Assert\LessThanOrEqual(value=Opinion::RATING_MAX_VALUE)
     * @Assert\NotBlank()
     */
    private ?int $rating = 1;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="opinions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
