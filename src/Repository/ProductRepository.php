<?php

namespace App\Repository;

use App\Entity\Product;
use App\Services\QueryHandler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    const PRODUCTS_PER_PAGE = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Return the total amount of products
     * @return int Count of products 
     */
    public function findAllCount()
    {
        $query = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery();

        return $query->getResult()[0];
    }

    /**
     * Return a specific product according to a specific keyword and with pagination 
     * @param string $keyword
     * @param int $offset
     * @param int $limit
     */
    public function findByKeyword(string $keyword, int $offset = 0, int $limit = 20): ?Product
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.name = :keyword')
            ->setParameter('keyword', $keyword)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function getPaginatedProducts(int $page)
    {
        $queryHandler = new QueryHandler();

        $query = $queryHandler->createQuery($this, 'p', self::PRODUCTS_PER_PAGE, $page);

        return new Paginator($query);
    }


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
