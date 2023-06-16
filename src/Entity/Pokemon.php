<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'pokemons')]
class Pokemon
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $number = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'float')]
    private ?float $height = null;

    #[ORM\Column(type: 'float')]
    private ?float $weight = null;

    #[ORM\ManyToOne(targetEntity: Gender::class)]
    private ?Gender $gender = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Type::class, mappedBy: "pokemons")]
    #[ORM\JoinTable(name: "pokemon_types")]
    private ?Collection $types;

    #[ORM\OneToOne(targetEntity: Pokemon::class)]
    private ?Pokemon $nextEvolution = null;

    #[ORM\OneToOne(targetEntity: Pokemon::class)]
    private ?Pokemon $previousEvolution = null;

    #[ORM\ManyToMany(targetEntity: Type::class)]
    private ?Collection $weaknesses;


    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->weaknesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getTypes(): ?Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->addPokemon($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->removeElement($type)) {
            $type->removePokemon($this);
        }

        return $this;
    }

    public function getWeaknesses(): ?Collection
    {
        return $this->weaknesses;
    }

    public function addWeakness(Type $weakness): self
    {
        if (!$this->weaknesses->contains($weakness)) {
            $this->weaknesses->add($weakness);
            $weakness->addPokemonWeakness($this);
        }
        return $this;
    }

    public function removeWeakness(Type $weakness): self
    {
        if ($this->weaknesses->removeElement($weakness)) {
            $weakness->removePokemonWeakness($this);
        }
        return $this;
    }



    public function getNextEvolution(): ?self
    {
        return $this->nextEvolution;
    }

    public function setNextEvolution(?self $nextEvolution): self
    {
        $this->nextEvolution = $nextEvolution;
        return $this;
    }

    public function getPreviousEvolution(): ?self
    {
        return $this->previousEvolution;
    }

    public function setPreviousEvolution(?self $previousEvolution): self
    {
        $this->previousEvolution = $previousEvolution;
        return $this;
    }
}
