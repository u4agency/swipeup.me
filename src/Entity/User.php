<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte associé à cet email')]
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
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: SwipeImage::class, orphanRemoval: true)]
    private Collection $swipeImages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: SwipeUp::class)]
    private Collection $swipeUps;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: WidgetUser::class, orphanRemoval: true)]
    private Collection $widgets;

    public function __toString()
    {
        return $this->username;
    }

    public function __construct()
    {
        $this->swipes = new ArrayCollection();
        $this->swipeImages = new ArrayCollection();
        $this->swipeUps = new ArrayCollection();
        $this->widgets = new ArrayCollection();
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
     * A visual identifier that represents this user.
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
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
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
}
