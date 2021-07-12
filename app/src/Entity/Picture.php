<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unq_mainpicture_product", fields={"product", "is_main"})})
 * @UniqueEntity(
 *     fields={"product", "is_main"},
 *     entityClass="App\Entity\Picture",
 *     message="dupplicate.main.picture",
 *     ignoreNull=true
 * )
 * @Vich\Uploadable
 */
class Picture
{
    use TimestampableEntity;

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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

    public function isMain(): ?bool
    {
        return $this->is_main;
    }

    public function setMain(bool $is_main): self
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
