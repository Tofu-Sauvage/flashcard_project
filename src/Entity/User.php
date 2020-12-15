<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Deck::class, mappedBy="author")
     */
    private $decks;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="author")
     */
    private $cards;

    /**
     * @ORM\ManyToMany(targetEntity=Deck::class, inversedBy="fans")
     * @ORM\JoinTable(name="favorites_fans")
     */
    private $favorites;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language_native;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $language_learn;


    public function __construct()
    {
        $this->decks = new ArrayCollection();
        $this->cards = new ArrayCollection();
        $this->favorites = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string) $this->email;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|deck[]
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDecks(deck $deck): self
    {
        if (!$this->decks->contains($deck)) {
            $this->decks[] = $deck;
            $deck->setAuthor($this);
        }

        return $this;
    }

    public function removeDecks(deck $deck): self
    {
        if ($this->decks->removeElement($deck)) {
            // set the owning side to null (unless already changed)
            if ($deck->getAuthor() === $this) {
                $deck->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCards(card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->card[] = $card;
            $card->setAuthor($this);
        }

        return $this;
    }

    public function removeCards(card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getAuthor() === $this) {
                $card->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|deck[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(deck $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(deck $favorite): self
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    public function getLanguageNative(): ?language
    {
        return $this->language_native;
    }

    public function setLanguageNative(?language $language_native): self
    {
        $this->language_native = $language_native;

        return $this;
    }

    public function getLanguageLearn(): ?language
    {
        return $this->language_learn;
    }

    public function setLanguageLearn(?language $language_learn): self
    {
        $this->language_learn = $language_learn;

        return $this;
    }

}
