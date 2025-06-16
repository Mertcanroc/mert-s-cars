<?php

namespace App\Repository;

use App\Entity\TestDrive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestDrive>
 *
 * @method TestDrive|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestDrive|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestDrive[]    findAll()
 * @method TestDrive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestDriveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestDrive::class);
    }

//    /**
//     * @return TestDrive[] Returns an array of TestDrive objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TestDrive
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
