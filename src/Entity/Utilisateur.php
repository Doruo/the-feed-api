<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UtilisateurRepository;
use App\State\UtilisateurProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(processor: UtilisateurProcessor::class),
        new Patch(),
        new Delete(),
    ],
    order: ["datePublication" => "DESC"],
    normalizationContext: ["groups" => ["serialization:etudiant:read"]],
)]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['adresseEmail'])]
class Utilisateur implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['serialization:etudiant:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max:200, minMessage: 'Il faut au moins 4 caractères!', maxMessage:'Il faut moins de 200 caractères!')]
    #[Groups(['serialization:etudiant:read'])]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email(message:'Adresse mail invalide.')]
    #[Groups(['serialization:etudiant:read'])]
    private ?string $adresseEmail = null;

    #[ApiProperty(
        description: 'Si il possède la formule premium', 
        readable: true, writable: false
    )]
    #[ORM\Column(options: ["default" => false])]
    #[Groups(['serialization:etudiant:read'])]
    private bool $premium;

    private ?UserGroupe $groupe = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];
    
    /**
     * @var ?string The hashed password
     */

    /*
    #[ORM\Column]
    private ?string $password = null;
    */

    /* ---------------------------------------------------- */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getAdresseEmail(): ?string
    {
        return $this->adresseEmail;
    }

    public function setAdresseEmail(string $adresseEmail): static
    {
        $this->adresseEmail = $adresseEmail;
        return $this;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function isPremium(): ?bool
    {
        return $this->premium;
    }

    public function setPremium(bool $premium): static
    {
        $this->premium = $premium;
        return $this;
    }
}

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ],
    normalizationContext: ["groups" => ["serialization:groupe:read"]],
)]
class UserGroupe {

    #[Groups(['serialization:groupe:read', 'serialization:etudiant:read'])]
    private ?int $id = null;

    #[Groups(['serialization:groupe:read', 'serialization:etudiant:read'])]
    private ?string $nomGroupe = null;

    public Collection $etudiants;

}
