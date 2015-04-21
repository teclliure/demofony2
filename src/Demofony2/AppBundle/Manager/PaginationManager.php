<?php

namespace Demofony2\AppBundle\Manager;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationManager
{
    protected $paginator;
    protected $request;

    /**
     * @param PaginatorInterface $paginatorInterface
     * @param RequestStack       $requestStack
     */
    public function __construct(PaginatorInterface $paginatorInterface, RequestStack $requestStack )
    {
            $this->paginator = $paginatorInterface;
            $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param $page  | page og the pagination
     * @param $items  | items by page
     * @param $parameterName | parameter name to route
     * @param $routeName | name of the route to use in paginator links
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPagination(QueryBuilder $queryBuilder, $page, $items, $parameterName, $routeName)
    {
        $result = $this->paginator->paginate(
            $queryBuilder,
            $page,
            $items,
            array(
                'pageParameterName' => $parameterName,
            )
        );
        $result->setUsedRoute($routeName);

        return $result;
    }

}
