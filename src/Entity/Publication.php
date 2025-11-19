<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\PublicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiProperty;

#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new Delete(),
    ],
    order: ["chiffreAffaire" => "DESC"]
)]
#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Publication
{
    #[ApiProperty(description: 'Contenu de la publication', writable: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: "Le message est trop court! (4 caractères minimum)",
        maxMessage: "Le message est trop long! (50 caractères maximum)"
     )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ApiProperty(description: 'Date de création de la publication', writable: false)]
    #[ORM\Column]
    private ?\DateTime $datePublication = null;

    #[ORM\PrePersist]
    public function prePersistDatePublication() : void {
        $this->datePublication = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDatePublication(): ?\DateTime
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTime $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }
}
