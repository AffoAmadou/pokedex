<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'types')]
class Type
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Pokemon::class, inversedBy: "types")]
    #[ORM\JoinTable(name: "pokemon_types")]
    private ?Collection $pokemons;

    #[ORM\ManyToMany(targetEntity: Pokemon::class, inversedBy: "weaknesses")]
    #[ORM\JoinTable(name: "pokemon_weakness")]
    private ?Collection $weakPokemons;

    #[ORM\ManyToMany(targetEntity: Pokemon::class, mappedBy: "weaknesses")]
    private ?Collection $pokemonWeaknesses;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
        $this->weakPokemons = new ArrayCollection();
        $this->pokemonWeaknesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPokemons(): ?Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons->add($pokemon);
            $pokemon->addType($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        if ($this->pokemons->removeElement($pokemon)) {
            $pokemon->removeType($this);
        }

        return $this;
    }

    public function getWeakPokemons(): ?Collection
    {
        return $this->weakPokemons;
    }

    public function addWeakPokemon(Pokemon $pokemon): self
    {
        if (!$this->weakPokemons->contains($pokemon)) {
            $this->weakPokemons->add($pokemon);
            $pokemon->addWeakness($this);
        }

        return $this;
    }

    public function removeWeakPokemon(Pokemon $pokemon): self
    {
        if ($this->weakPokemons->removeElement($pokemon)) {
            $pokemon->removeWeakness($this);
        }

        return $this;
    }


    public function getPokemonWeaknesses(): ?Collection
    {
        return $this->pokemonWeaknesses;
    }

    public function addPokemonWeakness(Pokemon $pokemon): self
    {
        if (!$this->pokemonWeaknesses->contains($pokemon)) {
            $this->pokemonWeaknesses->add($pokemon);
            $pokemon->addWeakness($this);
        }
        return $this;
    }

    public function removePokemonWeakness(Pokemon $pokemon): self
    {
        if ($this->pokemonWeaknesses->removeElement($pokemon)) {
            $pokemon->removeWeakness($this);
        }
        return $this;
    }
}
