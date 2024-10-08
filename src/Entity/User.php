<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")]
#[UniqueEntity(fields: ['username'], message: "Il y a déjà un compte avec ce nom d'utilisateur")]
#[UniqueEntity(fields: ['email'], message: "Il y a déjà un compte avec cette adresse email")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: SwipeImage::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $swipeImages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: SwipeUp::class, cascade: ['persist', 'remove'])]
    private Collection $swipeUps;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: WidgetUser::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $widgets;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $googleId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $facebookId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $hostedDomain;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    public function __toString()
    {
        return $this->username ?? $this->email;
    }

    public function __construct()
    {
        $this->swipeImages = new ArrayCollection();
        $this->swipeUps = new ArrayCollection();
        $this->widgets = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username ?? $this->email;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, SwipeImage>
     */
    public function getSwipeImages(): Collection
    {
        return $this->swipeImages;
    }

    public function addSwipeImage(SwipeImage $swipeImage): self
    {
        if (!$this->swipeImages->contains($swipeImage)) {
            $this->swipeImages->add($swipeImage);
            $swipeImage->setAuthor($this);
        }

        return $this;
    }

    public function removeSwipeImage(SwipeImage $swipeImage): self
    {
        if ($this->swipeImages->removeElement($swipeImage)) {
            // set the owning side to null (unless already changed)
            if ($swipeImage->getAuthor() === $this) {
                $swipeImage->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SwipeUp>
     */
    public function getSwipeUps(): Collection
    {
        return $this->swipeUps;
    }

    public function addSwipeUp(SwipeUp $swipeUp): self
    {
        if (!$this->swipeUps->contains($swipeUp)) {
            $this->swipeUps->add($swipeUp);
            $swipeUp->setAuthor($this);
        }

        return $this;
    }

    public function removeSwipeUp(SwipeUp $swipeUp): self
    {
        if ($this->swipeUps->removeElement($swipeUp)) {
            // set the owning side to null (unless already changed)
            if ($swipeUp->getAuthor() === $this) {
                $swipeUp->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WidgetUser>
     */
    public function getWidgets(): Collection
    {
        return $this->widgets;
    }

    public function addWidget(WidgetUser $widget): self
    {
        if (!$this->widgets->contains($widget)) {
            $this->widgets->add($widget);
            $widget->setUser($this);
        }

        return $this;
    }

    public function removeWidget(WidgetUser $widget): self
    {
        if ($this->widgets->removeElement($widget)) {
            // set the owning side to null (unless already changed)
            if ($widget->getUser() === $this) {
                $widget->setUser(null);
            }
        }

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): void
    {
        $this->googleId = $googleId;
    }

    public function getHostedDomain(): ?string
    {
        return $this->hostedDomain;
    }

    public function setHostedDomain(?string $hostedDomain): void
    {
        $this->hostedDomain = $hostedDomain;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): void
    {
        $this->facebookId = $facebookId;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
