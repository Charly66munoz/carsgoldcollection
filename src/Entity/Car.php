<?php

namespace App\Entity;

use App\Entity\Trait\UUIDTrait;
use App\Enum\Brand;
use App\Repository\CarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car 
{
    use UUIDTrait;
    private $today;

    #[Assert\Choice(callback: [Brand::class, 'cases'])]
    #[Assert\NotBlank]
    #[ORM\Column(enumType: Brand::class )]
    private ?Brand $brand = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 60)]
    private ?string $model = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[Assert\NotBlank]
    #[ORM\Column(type:'decimal', precision:10, scale: 2,)]
    #[Assert\Regex(pattern: '/^\d+(\.\d{1,2})?$/', message: 'El precio debe ser un número con hasta 2 decimales.')]
    private ?string $price = null;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1930,
        max: 2025,
        notInRangeMessage: 'El año debe estar entre {{ min }} y {{ max }}.',
    )]
    #[ORM\Column(type: 'integer')]
    private ?int $year = null;

    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/png'],
        extensionsMessage: 'Porfavor ingrese una imagen jpg ',
    )]
    private ?File $photoFile = null;

    public function __construct()
    {
        $this->today = (int)date('Y');
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    } 

    public function setBrand(?Brand $brand): static
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
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setPhotoFile(File $photoFile): static
    {
        $this->photoFile = $photoFile;

        return $this;
    }

    public function getPrice(): ?float
    {
        return (float) $this->price;
    }

    public function setPrice(float|string $price): static
    {
        $this->price = number_format((float)$price,2,".","");

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
