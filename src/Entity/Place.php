<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 */
class Place
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Place name must be at least {{ limit }} characters long",
     *      maxMessage = "Place name cannot be longer than {{ limit }} characters",
     * )
     */
    private $placename;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Regex(
     *     pattern = "/^[NS]([0-8][0-9](\.[0-5]\d){2}|90(\.00){2})$/",
     *     htmlPattern = "/^[NS]([0-8][0-9](\.[0-5]\d){2}|90(\.00){2})$/",
     *     match = true,
     *     message = "The value should look like N45.12.34 or S07.56.20"
     * )
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Regex(
     *     pattern = "/^[EW]((0\d\d|1[0-7]\d)(\.[0-5]\d){2}|180(\.00){2})$/",
     *     htmlPattern = "/^[EW]((0\d\d|1[0-7]\d)(\.[0-5]\d){2}|180(\.00){2})$/",
     *     match = true,
     *     message = "The value should look like E123.12.34 or W06.56.20"
     * )
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="place")
     */
    private $people;

    public function __construct()
    {
        $this->people = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlacename(): ?string
    {
        return $this->placename;
    }

    public function setPlacename(string $placename): self
    {
        $this->placename = $placename;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->setPlace($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getPlace() === $this) {
                $person->setPlace(null);
            }
        }

        return $this;
    }
}
