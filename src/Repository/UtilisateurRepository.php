<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function add(Utilisateur $object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    /**
     * @return Utilisateur[] Returns an array of all Utilisateur objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('u')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}