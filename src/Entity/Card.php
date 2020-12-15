<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $answer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tense;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mood;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sentence1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sentence2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="card_author")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=category::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=deck::class, inversedBy="cards")
     */
    private $decks;

    public function __construct()
    {
        $this->decks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getTense(): ?string
    {
        return $this->tense;
    }

    public function setTense(?string $tense): self
    {
        $this->tense = $tense;

        return $this;
    }

    public function getMood(): ?string
    {
        return $this->mood;
    }

    public function setMood(?string $mood): self
    {
        $this->mood = $mood;

        return $this;
    }

    public function getSentence1(): ?string
    {
        return $this->sentence1;
    }

    public function setSentence1(?string $sentence1): self
    {
        $this->sentence1 = $sentence1;

        return $this;
    }

    public function getSentence2(): ?string
    {
        return $this->sentence2;
    }

    public function setSentence2(?string $sentence2): self
    {
        $this->sentence2 = $sentence2;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|deck[]
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDeck(deck $deck): self
    {
        if (!$this->decks->contains($deck)) {
            $this->decks[] = $deck;
        }

        return $this;
    }

    public function removeDeck(deck $deck): self
    {
        $this->decks->removeElement($deck);

        return $this;
    }
}
