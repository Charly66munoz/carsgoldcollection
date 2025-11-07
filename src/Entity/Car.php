<?php

namespace App\Entity;

use App\Entity\Trait\UUIDTrait;
use App\Enum\Brand;
use App\Repository\CarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Uuid;



#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car 
{
    use UUIDTrait;

    #[Assert\Choice(callback: [Brand::class, 'value'])]
    #[Assert\NotBlank]
    #[ORM\Column(enumType: Brand::class )]
    private ?Brand $brand = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 60)]
    private ?string $model = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    
    #[Assert\NotBlank]
    #[ORM\Column(type:'decimal', scale: 2,)]
    private ?float $price = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: 'integer')]
    private ?int $year = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }
}
