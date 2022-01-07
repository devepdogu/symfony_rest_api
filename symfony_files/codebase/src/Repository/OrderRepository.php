<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findAllJoin()
    {
        return $this->createQueryBuilder('o')
            ->select(['o.quantity,o.address,o.shipping_date', 'product.name as productName,product.price as productPrice', 'user.username'])
            ->leftJoin('o.product', 'product')
            ->leftJoin('o.user', 'user')
            ->getQuery()
            ->execute();
    }
    public function findAllJoinWithId($id)
    {
        return $this->createQueryBuilder('o')
            ->select(['o.quantity,o.address,o.shipping_date', 'product.name as productName,product.price as productPrice', 'user.username'])
            ->where('o.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('o.product', 'product')
            ->leftJoin('o.user', 'user')
            ->getQuery()
            ->execute();
    }

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
