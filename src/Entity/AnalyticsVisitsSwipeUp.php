<?php

namespace App\Entity;

use App\Repository\AnalyticsVisitsSwipeUpRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AnalyticsVisitsSwipeUpRepository::class)]
class AnalyticsVisitsSwipeUp
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $userId = null;

    #[ORM\ManyToOne(inversedBy: 'analyticsVisitsSwipeUps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SwipeUp $swipeup = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $userAgent = null;

    #[ORM\Column(length: 255)]
    private ?string $userIp = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $siteReferer = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $visitedAt = null;

    public function __construct()
    {
        $this->visitedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getSwipeup(): ?SwipeUp
    {
        return $this->swipeup;
    }

    public function setSwipeup(?SwipeUp $swipeup): static
    {
        $this->swipeup = $swipeup;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getUserIp(): ?string
    {
        return $this->userIp;
    }

    public function setUserIp(string $userIp): static
    {
        $this->userIp = $userIp;

        return $this;
    }

    public function getSiteReferer(): ?string
    {
        return $this->siteReferer;
    }

    public function setSiteReferer(?string $siteReferer): static
    {
        $this->siteReferer = $siteReferer;

        return $this;
    }

    public function getVisitedAt(): ?\DateTimeImmutable
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeImmutable $visitedAt): static
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }
}
