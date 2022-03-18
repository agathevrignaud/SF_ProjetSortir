<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function findSortieByFilter($filter): array
    {

        $qb = $this->createQueryBuilder('sortie');

        if ($filter['site']) {
            $qb
                ->andWhere('sortie.site = :site')
                ->setParameter('site', $filter['site']);
        }

        if ($filter['nom']) {
            $qb
                ->andWhere('sortie.nom LIKE :nom')
                ->setParameter('nom', '%'.$filter['nom'].'%');
        }

        if ($filter['dateDebut'] || $filter['dateFin']) {
            $dateDebut = $filter['dateDebut'] ? date_format($filter['dateDebut'], 'Y-m-d') : date('Y-m-d');
            $dateFin = $filter['dateFin'] ? date_format($filter['dateFin'], 'Y-m-d') : date('Y-m-d');
            $qb
                ->andWhere('sortie.dateHeureDebut BETWEEN :dateDebut and :dateFin')
                ->setParameter('dateDebut', $dateDebut)
                ->setParameter('dateFin', $dateFin);
        }

        if ($filter['organisateur']) {
            $qb
                ->andWhere('sortie.organisateur = :organisateur')
                ->setParameter('organisateur', $filter['userId']);
        }

        if ($filter['estInscrit']) {
            $qb
                ->andWhere(':userId MEMBER OF sortie.sortiesParticipants')
                ->setParameter('userId', $filter['userId']);
        }

        if ($filter['estNonInscrit']) {
            $qb
                ->andWhere('sortie.organisateur != :organisateur')
                ->setParameter('organisateur', $filter['userId'])
                ->andWhere(':userId NOT MEMBER OF sortie.sortiesParticipants')
                ->setParameter('userId', $filter['userId']);
        }


        if ($filter['estPassee']) {
            $today = date('Y-m-d');
            $todayMinusAMonth = date('Y-m-d', strtotime("-1 months"));
            $qb
                ->andWhere("sortie.dateHeureDebut BETWEEN :today AND :todayMinusAMonth ")
                ->setParameter('today', $today)
                ->setParameter('todayMinusAMonth', $todayMinusAMonth)
                ->andWhere("sortie.etat = :libelle ")
                ->setParameter('libelle', 'Passée' );
        }

        $query = $qb->getQuery();
        return $query->execute();
    }

}
