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
}
