<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Picture;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @ORM\InheritanceType(value="JOINED")
 * @ORM\DiscriminatorMap(value={
 *  Document::DOCUMENT=Document::class,
 *  Document::PICTURE=Picture::class
 * })
 */
class Document
{
    const DOCUMENT = 'document';
    const PICTURE = 'picture';

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
     * @ORM\Column(type="string", length=500)
     */
    private $path;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
