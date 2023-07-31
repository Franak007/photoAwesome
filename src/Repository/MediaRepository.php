<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 *
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    public function getQbAll() : QueryBuilder //Qb pour Query builder
    {
// SELECT * FROM media as m
        return $this->createQueryBuilder('m');
    }

// SELECT * FROM media ORDER BY created_at LIMIT 5;

    public function findLastFiveMedia()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'desc')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
