<?php

namespace App\Entity;

use App\Repository\SwipeImageRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SwipeImageRepository::class)]
#[Vich\Uploadable]
class SwipeImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $backgroundName;

    #[Vich\UploadableField(mapping: "swipe_background", fileNameProperty: "backgroundName")]
    private $backgroundFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $thumbnailName;

    #[Vich\UploadableField(mapping: "swipe_thumbnails", fileNameProperty: "thumbnailName")]
    private $thumbnailFile;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\ManyToOne(inversedBy: 'swipeImages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $author = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $alt = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\OneToMany(mappedBy: 'background', targetEntity: Swipe::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['sequence' => 'ASC'])]
    private Collection $swipes;

    public function __toString(): string
    {
        return $this->backgroundName;
    }

    public function __construct()
    {
        $this->uploadedAt = new \DateTimeImmutable();
        $this->isPublic = false;

        $this->thumbnailName = $this->backgroundName;
        $this->thumbnailFile = $this->backgroundFile;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setBackgroundFile(File $backgroundFile = null)
    {
        $this->backgroundFile = $backgroundFile;

        if ($backgroundFile) {
            $this->uploadedAt = new \DateTimeImmutable();
        }
    }

    public function getBackgroundFile()
    {
        return $this->backgroundFile;
    }

    public function setBackgroundName($backgroundName)
    {
        $this->backgroundName = $backgroundName;
    }

    public function getBackgroundName()
    {
        return $this->backgroundName;
    }

    public function setThumbnailFile(File $thumbnailFile = null)
    {
        $this->thumbnailFile = $thumbnailFile;

        if ($thumbnailFile) {
            $this->uploadedAt = new \DateTimeImmutable();
        }
    }

    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailName($thumbnailName)
    {
        $this->thumbnailName = $thumbnailName;
    }

    public function getThumbnailName()
    {
        return $this->thumbnailName;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getSwipes(): Collection
    {
        return $this->swipes;
    }

    public function setSwipes(Collection $swipes): void
    {
        $this->swipes = $swipes;
    }
}
