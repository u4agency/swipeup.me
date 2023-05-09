<?php

namespace App\Entity;

use App\Repository\WidgetUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WidgetUserRepository::class)]
class WidgetUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Widget $widget = null;

    #[ORM\ManyToOne(inversedBy: 'widgets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $boughtAt = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBoughtAt(): ?\DateTimeImmutable
    {
        return $this->boughtAt;
    }

    public function setBoughtAt(\DateTimeImmutable $boughtAt): self
    {
        $this->boughtAt = $boughtAt;

        return $this;
    }
}
