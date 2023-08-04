<?php

namespace App\Entity;

use App\Repository\SwipeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SwipeRepository::class)]
class Swipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'swipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SwipeUp $swipeup = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SwipeImage $background = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'swipe', cascade: ['persist', 'remove'])]
    private ?WidgetSwipe $widgetBody = null;

    #[ORM\OneToOne(inversedBy: 'swipe', cascade: ['persist', 'remove'])]
    private ?WidgetSwipe $widgetFooter = null;

    public function __toString(): string
    {
        return "Section de @".$this->swipeup->getSlug()." créée le ". $this->getCreatedAt()->format('d/m/Y \à H:i:s');
    }

    public function __construct()
    {
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
}
