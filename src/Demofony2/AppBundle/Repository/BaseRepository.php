<?php
/**
 * Demofony2
 * 
 * @author: Marc Morales Valldepérez <marcmorales83@gmail.com>
 * 
 * Date: 24/11/14
 * Time: 17:07
 */
namespace Demofony2\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;


class BaseRepository extends EntityRepository
{
    /**
     * @param \Doctrine\ORM\Query $query $query
     * @param int                 $page
     * @param int                 $limit
     *
     * @return array
     */
    protected function paginateQuery(Query $query, $page, $limit)
    {
        $query
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
        $paginator = new Paginator($query, false);

        return $paginator->getIterator()->getArrayCopy();
    }
}
