<?php

namespace App\Entity;

use App\Repository\RecetteRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[UniqueEntity('Name')]
#[ORM\HasLifecycleCallbacks]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private string $nom ;

    // #[Assert\NotBlank()]
    // #[Assert\LessThan(1441)]
    #[ORM\Column(nullable: true)]
    #[Assert\Range(
        min: 60,
        max: 86400,
        notInRangeMessage: 'Le temps doit être compris entre 1 minute et 24 heures.'
    )]
    private ?int $temps = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\LessThan(51)]
    private ?int $nbPersonne = null;

    // #[Assert\NotBlank()]
    // #[Assert\LessThan(6)]
    #[ORM\Column(nullable: true)]
    #[Assert\Choice(
        choices: [1, 2, 3, 4, 5],
        message: 'La difficulté doit être comprise entre 1 et 5.'
    )]
    private ?int $difficulté = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank()]
    private string $description;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(1001)]
    private float $prix ;

    #[ORM\Column()]
    private ?bool $isFavorite = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $dateCréation;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $dateModification;

    #[ORM\OneToMany(mappedBy: 'name', targetEntity: Ingredient::class)]
    private Collection $listeIngredients;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTemps(): ?int
    {
        return $this->temps;
    }

    public function setTemps(?int $temps): static
    {
        $this->temps = $temps;

        return $this;
    }

    public function getNbPersonne(): ?int
    {
        return $this->nbPersonne;
    }

    public function setNbPersonne(?int $nbPersonne): static
    {
        $this->nbPersonne = $nbPersonne;

        return $this;
    }

    public function getDifficulté(): ?int
    {
        return $this->difficulté;
    }

    public function setDifficulté(?int $difficulté): static
    {
        $this->difficulté = $difficulté;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setisFavorite(?bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getDateCréation(): ?\DateTimeInterface
    {
        return $this->dateCréation;
    }

    public function setDateCréation(\DateTimeInterface $dateCréation): static
    {
        $this->dateCréation = $dateCréation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): static
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getListeIngredients(): Collection
    {
        return $this->listeIngredients;
    }

    public function setListeIngredients(array $listeIngredients): static
    {
        $this->listeIngredients = $listeIngredients;

        return $this;
    }

    public function addListeIngredient(Ingredient $ingredient): self
    {
        if (!$this->listeIngredients->contains($ingredient)) {
            $this->listeIngredients[] = $ingredient;
        }

        return $this;
    }

    public function removeListeIngredient(Ingredient $ingredient): self
    {
        $this->listeIngredients->removeElement($ingredient);

        return $this;
    }


    #[ORM\PrePersist()]
    public function setUpdatedAtValue(): void
    {
        $this->dateModification = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->listeIngredients = new ArrayCollection();
        $this->dateCréation = new \DateTimeImmutable();
        $this->dateModification = new \DateTimeImmutable();

    }
}
