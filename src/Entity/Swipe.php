<?php

namespace App\Entity;

use App\Repository\SwipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SwipeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Swipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'swipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SwipeUp $swipeup = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'swipes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?SwipeImage $background = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'swipeBody', targetEntity: WidgetSwipe::class, cascade: ['persist', 'remove'])]
    private ?WidgetSwipe $widgetBody = null;

    #[ORM\OneToOne(inversedBy: 'swipeFooter', targetEntity: WidgetSwipe::class, cascade: ['persist', 'remove'])]
    private ?WidgetSwipe $widgetFooter = null;

    #[ORM\OneToMany(mappedBy: 'swipe', targetEntity: AnalyticsVisitsSwipe::class, cascade: ['remove'])]
    private Collection $analyticsVisitsSwipe;

    #[ORM\Column(nullable: true)]
    private ?int $sequence = null;

    public function __toString(): string
    {
        return "Section de @" . $this->swipeup->getSlug() . " créée le " . $this->getCreatedAt()->format('d/m/Y \à H:i:s');
    }

    public function __construct()
    {
        $this->analyticsVisitsSwipe = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getSwipeup(): ?SwipeUp
    {
        return $this->swipeup;
    }

    public function setSwipeup(?SwipeUp $swipeup): self
    {
        $this->swipeup = $swipeup;

        return $this;
    }

    public function getBackground(): ?SwipeImage
    {
        return $this->background;
    }

    public function setBackground(?SwipeImage $background): self
    {
        $this->background = $background;

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

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function setUpdatedAtValue(): void
    {
        $this->swipeup->setUpdatedAt(new \DateTimeImmutable('now'));
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getWidgetBody(): ?WidgetSwipe
    {
        return $this->widgetBody;
    }

    public function setWidgetBody(?WidgetSwipe $widgetBody): self
    {
        $this->widgetBody = $widgetBody;

        return $this;
    }

    public function getWidgetFooter(): ?WidgetSwipe
    {
        return $this->widgetFooter;
    }

    public function setWidgetFooter(?WidgetSwipe $widgetFooter): self
    {
        $this->widgetFooter = $widgetFooter;

        return $this;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(?int $sequence): static
    {
        $this->sequence = $sequence;

        return $this;
    }
}
