<?php

namespace App\Repository;

use App\Entity\Deck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Deck|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deck|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deck[]    findAll()
 * @method Deck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deck::class);
    }

     /**
      * @return Deck[] Returns an array of Deck objects
      */
    public function findLastestDecks()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function findByUser($userId)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.author= :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Deck
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
