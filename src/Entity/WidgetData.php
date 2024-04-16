<?php

namespace App\Entity;

use App\Repository\WidgetDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WidgetDataRepository::class)]
#[ORM\HasLifecycleCallbacks]
class WidgetData
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Widget $widget = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'widgetData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WidgetSwipe $widgetSwipe = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $dataName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $dataValue = null;

    public function __toString(): string
    {
        return "Données de " . $this->widget->getName() . " sur " . $this->widgetSwipe->getSwipe();
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

    public function getWidget(): ?Widget
    {
        return $this->widget;
    }

    public function setWidget(?Widget $widget): self
    {
        $this->widget = $widget;

        return $this;
    }

    public function getWidgetSwipe(): ?WidgetSwipe
    {
        return $this->widgetSwipe;
    }

    public function setWidgetSwipe(?WidgetSwipe $widgetSwipe): self
    {
        $this->widgetSwipe = $widgetSwipe;

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
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable('now');
        $this->widgetSwipe->getSwipe()->setUpdatedAtValue();
        $this->widgetSwipe->getSwipe()->getSwipeup()->setUpdatedAtValue();
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDataName(): ?string
    {
        return $this->dataName;
    }

    public function setDataName(string $dataName): self
    {
        $this->dataName = $dataName;

        return $this;
    }

    public function getDataValue(): ?string
    {
        return $this->dataValue;
    }

    public function setDataValue(string $dataValue): self
    {
        $this->dataValue = $dataValue;

        return $this;
    }
}
