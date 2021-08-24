<?php

namespace App\Entity;

use Axiocode\ApiBundle\Annotation\ExposeResource;
use Axiocode\ApiBundle\Annotation\ExposeRoute;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Product\Repository\PictureRepository")
 * @ORM\Table()
 * @UniqueEntity(
 *     fields={"product", "is_main"},
 *     message="dupplicate.main.picture",
 *     ignoreNull=true
 * )
 * @Vich\Uploadable
 * @ExposeResource(
 *   deleteOne=@ExposeRoute(name="api_picture_delete", isGranted="ROLE_ADMIN", map={"id", "name", "product.id"}),
 *   updateOne=@ExposeRoute(name="api_picture_update", isGranted="ROLE_ADMIN", input="name,is_main", map={"id", "name", "isMain", "product.id"}),
 * )
 */
class Picture
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=1, max=255)
     * @Assert\NotBlank(allowNull=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private ?string $path = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $is_main = null;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="path")
     * @var File|null
     */
    protected ?File $file = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

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

    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|UploadedFile|null $file
     */
    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        if ($file) {
            $this->setUpdatedAt(new DateTime('now'));
        }

        return $this;
    }
}
