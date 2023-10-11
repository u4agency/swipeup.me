<?php

namespace App\Entity;

use App\Repository\SwipeUpRepository;
use App\Service\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SwipeUpRepository::class)]
#[UniqueEntity(fields: ['slug'], message: "Le lien de ce SwipeUp n'est pas disponible")]
#[Vich\Uploadable]
class SwipeUp
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'swipeUps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?bool $featuredSwipeUp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $font = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $logoName;

    #[Vich\UploadableField(mapping: "swipeup_logo", fileNameProperty: "logoName")]
    private $logoFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $iconName;

    #[Vich\UploadableField(mapping: "swipeup_icon", fileNameProperty: "iconName")]
    private $iconFile;

    #[ORM\OneToMany(mappedBy: 'swipeup', targetEntity: Swipe::class, orphanRemoval: true)]
    private Collection $swipes;

    #[ORM\OneToMany(mappedBy: 'swipeup', targetEntity: AnalyticsVisitsSwipeUp::class)]
    private Collection $analyticsVisitsSwipeUp;

    public function __toString(): string
    {
        return $this->title . ' (@' . $this->slug . ')';
    }

    public function __construct()
    {
        $this->swipes = new ArrayCollection();
        $this->analyticsVisitsSwipeUp = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        $this->logoName = $this->generateRandomImage();

        $this->status = Status::PENDING;

        $this->featuredSwipeUp = false;
    }

    /**
     * @throws Exception
     */
    private function generateRandomImage(): string
    {
        return "defaultLogo-" . random_int(1, 3) . ".webp";
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status = "public" | "unlisted" | "private"): self
    {
        $this->status = $status;

        return $this;
    }

    public function isFeaturedSwipeUp(): ?bool
    {
        return $this->featuredSwipeUp;
    }

    public function setFeaturedSwipeUp(bool $featuredSwipeUp): self
    {
        $this->featuredSwipeUp = $featuredSwipeUp;

        return $this;
    }

    public function getFont(): ?string
    {
        return $this->font;
    }

    public function setFont(?string $font): self
    {
        $this->font = $font;

        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getLogoName()
    {
        return $this->logoName ?? $this->generateRandomImage();
    }

    public function getRealLogoName()
    {
        return $this->logoName;
    }

    /**
     * @param mixed $logoName
     */
    public function setLogoName($logoName): void
    {
        $this->logoName = $logoName;
    }

    /**
     * @return mixed
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @param mixed $logoFile
     */
    public function setLogoFile(File $logoFile = null): void
    {
        $this->logoFile = $logoFile;
        if ($logoFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return mixed
     */
    public function getIconName()
    {
        return $this->iconName;
    }

    /**
     * @param mixed $iconName
     */
    public function setIconName($iconName): void
    {
        $this->iconName = $iconName;
    }

    /**
     * @return mixed
     */
    public function getIconFile()
    {
        return $this->iconFile;
    }

    /**
     * @param mixed $iconFile
     */
    public function setIconFile(File $iconFile = null): void
    {
        $this->iconFile = $iconFile;
        if ($iconFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return Collection<int, Swipe>
     */
    public function getSwipes(): Collection
    {
        return $this->swipes;
    }

    public function addSwipe(Swipe $swipe): self
    {
        if (!$this->swipes->contains($swipe)) {
            $this->swipes->add($swipe);
            $swipe->setSwipeup($this);
        }

        return $this;
    }

    public function removeSwipe(Swipe $swipe): self
    {
        if ($this->swipes->removeElement($swipe)) {
            // set the owning side to null (unless already changed)
            if ($swipe->getSwipeup() === $this) {
                $swipe->setSwipeup(null);
            }
        }

        return $this;
    }

    public function getAnalyticsVisitsSwipeUp(): Collection
    {
        return $this->analyticsVisitsSwipeUp;
    }

    public function setAnalyticsVisitsSwipeUp(Collection $analyticsVisitsSwipeUp): void
    {
        $this->analyticsVisitsSwipeUp = $analyticsVisitsSwipeUp;
    }
}
