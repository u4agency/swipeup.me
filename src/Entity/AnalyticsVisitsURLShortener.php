<?php

namespace App\Entity;

use App\Repository\AnalyticsVisitsURLShortenerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AnalyticsVisitsURLShortenerRepository::class)]
class AnalyticsVisitsURLShortener
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $userId = null;

    #[ORM\ManyToOne(inversedBy: 'analyticsVisitsURLShortener')]
    #[ORM\JoinColumn(nullable: false)]
    private ?URLShortener $URLShortener = null;

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

    public function getURLShortener(): ?URLShortener
    {
        return $this->URLShortener;
    }

    public function setURLShortener(?URLShortener $URLShortener): static
    {
        $this->URLShortener = $URLShortener;

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
