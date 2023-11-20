<?php

namespace App\Entity;

use App\Repository\WNewsletterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WNewsletterRepository::class)]
class WNewsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: WidgetSwipe::class, cascade: ['persist', 'remove'], inversedBy: 'WNesletter')]
    #[ORM\JoinColumn(nullable: true)]
    private ?WidgetSwipe $widgetSwipe = null;

    public function __toString()
    {
        return $this->getEmail() . " (" . $this->getCreatedAt()->format('d/m/Y \à H:i:s') . ")";
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getWidgetSwipe(): ?WidgetSwipe
    {
        return $this->widgetSwipe;
    }

    public function setWidgetSwipe(?WidgetSwipe $widgetSwipe): static
    {
        $this->widgetSwipe = $widgetSwipe;

        return $this;
    }
}
