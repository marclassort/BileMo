<?php

namespace App\Services;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class QueryHandler
{
    public function createQuery(ServiceEntityRepository $repository, string $table, int $numberPerPage, int $page)
    {
        return $repository->createQueryBuilder($table)
            ->setMaxResults($numberPerPage)
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->getQuery()
            ->getResult();
    }

    public function createQueryWithWhere(ServiceEntityRepository $repository, string $table, string $secondTable, int $numberPerPage, int $page, $value)
    {
        return $repository->createQueryBuilder($table)
            ->innerJoin('App\Entity\Customer', $secondTable, 'WITH', $secondTable . ' = ' . $table . '.customer')
            ->where($secondTable . '.id = :val')
            ->setParameter('val', $value)
            ->setMaxResults($numberPerPage)
            ->setFirstResult(($page - 1) * $numberPerPage)
            ->getQuery()
            ->getResult();
    }
}
