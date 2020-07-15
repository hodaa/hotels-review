<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use PDO;

/**
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends ServiceEntityRepository
{
    /**
     * HotelRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    /**
     * @param $name
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($name)
    {
        $hotel= new Hotel();
        $hotel->setName($name);
        $this->getEntityManager()->persist();
        $this->getEntityManager()->flush();
    }

    /**
     * @param $hotel_id
     * @param $start_date
     * @param $end_date
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getHotelsReviews($hotel_id, $start_date, $end_date)
    {
        $start_date = \DateTime::createFromFormat('Y-m-d', $start_date);
        $end_date = \DateTime::createFromFormat('Y-m-d', $end_date);
        $days = date_diff($start_date, $end_date)->days;

        $query=  $this->createQueryBuilder('h')
            ->innerJoin(
                Review::class,
                'r',
                Join::WITH,
                'h.id = r.hotel'
            )->select('h.id as hotel,COUNT(r.id) AS review_count, FLOOR(avg(r.score)) as score_avg');



        if ($days < 30) {
            $query->addSelect('DAY(r.created_at) as date_group');
        } elseif ($days < 89) {
            $query->addSelect('WEEK(r.created_at) as date_group');
        } else {
            $query->addSelect('MONTH(r.created_at) as date_group');
        }


        return $query->where('h.id = :id')
            ->andWhere('r.created_at >= :start_date')
            ->andWhere('r.created_at <= :end_date')
            ->setParameter('start_date', $start_date)
            ->setParameter('end_date', $end_date)
            ->setParameter('id', $hotel_id)
            ->groupBy('h.id,date_group')
            ->orderBy('h.id,date_group', 'ASC')
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Hotel[] Returns an array of Hotel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hotel
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
