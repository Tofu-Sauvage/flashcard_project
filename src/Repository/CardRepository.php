<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

     /**
      * @return Card[] Returns an array of Card objects
      */
    
    public function findLatestCards()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUser($userId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.author= :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    // -------------- Version avec Doctrine (non-fonctionnel) ----------------------

    // public function custom($author, $deck)
    // {
    //     $qb = $this->_em->createQueryBuilder();

    //     $side = $qb ->select('d')
    //                  ->from('App\Entity\Deck', 'd')
    //                  ->andWhere('d = :deck')
    //                  ->setParameter('deck', $deck)
    //                  ->getQuery()
    //                  ->getResult()
    //                  ;
                     
    //     $side = $side[0];
    //     // dd($side);

    //     $main = $qb->select('c')
    //                 ->from('App\Entity\Card', 'c')
    //                 ->andWhere('c.author = :author')
    //                 ->andWhere($qb->expr()->notIn('c.id', $this->_em->createQuery('SELECT card_id FROM card_deck cd WHERE cd.deck_id = $deck->getId()')))
    //                 ->setParameter('author', $author)
    //                 // dd($main);

    //                 ->getQuery()
    //                 ->getResult()
    //                 ;

    // -------------------- Version avec SQl brut (fonctionnel MAIS ne renvoie pas d'objets) ---------------------
    
    //     $em = $this->getEntityManager()->getConnection();
   
    //     $sql = 'SELECT *
    //             FROM card
    //             WHERE card.author_id = 23
    //             AND NOT card.id IN 
    //             (SELECT card_id FROM card_deck cd WHERE cd.deck_id = 11)
    //     ';
    //     $query = $em->prepare($sql);
    //     // $query->setParameter(1, $authorId)
    //     //       ->setParameter(2, $deckId)
    //     //       ;

    //    $query->execute();
    //    $cards = $query->fetchAll();
    
    //    return $main;
    // }
    

    /*
    public function findOneBySomeField($value): ?Card
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
