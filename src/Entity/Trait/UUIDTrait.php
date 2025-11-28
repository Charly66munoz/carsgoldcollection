<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;


trait UUIDTrait
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    private ?Uuid $id = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

  
}



//Cuando creas un objeto nuevo de tu entidad y lo persistís en la base de datos 
//($entityManager->persist($car) + $entityManager->flush()), Doctrine automáticamente genera un UUID v4 para ese registro.

//No necesitas hacer $this->id = Uuid::v4() en el constructor.
//La ventaja: la generación del UUID queda completamente delegada a Doctrine, y es consistente con el tipo Uuid de Symfony.