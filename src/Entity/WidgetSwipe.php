<?php

namespace App\Entity;

use App\Repository\WidgetSwipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WidgetSwipeRepository::class)]
class WidgetSwipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Widget $widget = null;

    #[ORM\OneToMany(mappedBy: 'widgetSwipe', targetEntity: WidgetData::class)]
    private Collection $widgetData;

    public function __toString(): string
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->widgetData = new ArrayCollection();
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

    /**
     * @return Collection<int, WidgetData>
     */
    public function getWidgetData(): Collection
    {
        return $this->widgetData;
    }

    public function addWidgetData(WidgetData $widgetData): self
    {
        if (!$this->widgetData->contains($widgetData)) {
            $this->widgetData->add($widgetData);
            $widgetData->setWidgetSwipe($this);
        }

        return $this;
    }

    public function removeWidgetData(WidgetData $widgetData): self
    {
        if ($this->widgetData->removeElement($widgetData)) {
            // set the owning side to null (unless already changed)
            if ($widgetData->getWidgetSwipe() === $this) {
                $widgetData->setWidgetSwipe(null);
            }
        }

        return $this;
    }
}
