<?php

namespace App\Repository;

use App\Entity\Films;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Films|null find($id, $lockMode = null, $lockVersion = null)
 * @method Films|null findOneBy(array $criteria, array $orderBy = null)
 * @method Films[]    findAll()
 * @method Films[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Films::class);
    }


    public function addFilm($Titre, $Synopsis, $realisateur, $genre, $duree)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT f FROM App\Entity\Films f');
        $Res = $query->getResult();

        if ($Res != null) {

            $newFilm = new Films();
            $newFilm->setTitle($Titre);
            $newFilm->setSynopsis($Synopsis);
            $newFilm->setRealisateur($realisateur);
            $newFilm->setGenre($genre);
            $newFilm->setDuree($duree);

            $entityManager->persist($newFilm);
            $entityManager->flush();
        }
    }

    // /**
    //  * @return Films[] Returns an array of Films objects
    //  */

    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('f')
    //         ->andWhere('f.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('f.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }


    /*
    public function findOneBySomeField($value): ?Films
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
