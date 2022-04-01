<?php

namespace App\Repository;

use App\Entity\User;
use App\Services\QueryHandler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const USERS_PER_PAGE = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function getPaginatedUsers(int $page)
    {
        $queryHandler = new QueryHandler();

        $query = $queryHandler->createQuery($this, 'p', self::USERS_PER_PAGE, $page);

        return new Paginator($query);
    }
}
