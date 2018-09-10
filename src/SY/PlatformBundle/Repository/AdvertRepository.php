<?php

namespace SY\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends EntityRepository
{

    /**
     * @param array $categoryNames
     *
     * @return mixed
     */
    public function getAdvertWithCategories(array $categoryNames)
    {
        $qb = $this ->createQueryBuilder('a');

        $qb
            ->innerJoin('a.categories', 'cat')
            ->addSelect('cat');

        $qb->where($qb->expr()->in('cat.name', $categoryNames));

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getAdverts($page, $nbPerPage)
    {
        $qb = $this ->createQueryBuilder('a');
        $qb->leftJoin('a.image', 'i')
           ->addSelect('i')
           ->leftJoin('a.categories', 'c')
           ->addSelect('c')
           ->leftJoin('a.skills', 's')
           ->addSelect('s')
           ->orderBy('a.date', 'DESC')
           ->getQuery();

        $qb
            ->setFirstResult(($page - 1) * $nbPerPage)
            ->setMaxResults($nbPerPage);


        return new Paginator($qb, true);
    }



    // EXAMPLES ************************************************************

    public function myFindAll()
    {
        return $this
            ->createQueryBuilder('a')
            ->getQuery()
            ->getResult();
    }

    public function myFindOne($id)
    {
        $qb = $this->createQueryBuilder( 'a' );

        $qb
            ->where( 'a.id = :id' )
            ->setParameter( 'id', $id );

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findByAuthorAndDate($author, $year)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->where('a.author = :author')
                ->setParameter('author', $author)
            ->andWhere('a.date < :year')
                ->setParameter('year', $year)
            ->orderBy('a.date', 'DESC');

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function whereCurrentYear(QueryBuilder $qb)
    {
        $qb
            ->andWhere('a.date BETWEEN :start AND :end')
            ->setParameter('start', new \DateTime(date('Y') . '-01-01'))
            ->setParameter('end', new \DateTime(date('Y') . '-12-31'));
    }

    public function myFind()
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->where('a.author = :author')
            ->setParameter('author', 'Marine');

        $this->whereCurrentYear($qb);

        $qb->orderBy('a.date', 'DESC');


        return $qb
            ->getQuery()
            ->getResult();
    }

    public function myFindAllDQL()
    {
        $query = $this->_em->createQuery('SELECT a FROM SYPlatformBundle:Advert a');
        $results = $query->getResult();

        return $results;
    }


    public function getAdvertWithApplicatins()
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.applications', 'app')
            ->addSelect('app');

        return $qb
            ->getQuery()
            ->getResult();
    }
}
