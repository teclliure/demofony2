<?php

namespace Demofony2\AppBundle\Manager;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationManager
{
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $itemsByPage;

    /**
     * @var string
     */
    protected $firstPaginationRoute;

    /**
     * @var string
     */
    protected $secondPaginationRoute;

    /**
     * @var int
     */
    protected $firstPaginationPage;

    /**
     * @var int
     */
    protected $secondPaginationPage;

    /**
     * @var QueryBuilder
     */
    protected $firstPaginationQueryBuilder;

    /**
     * @var QueryBuilder
     */
    protected $secondPaginationQueryBuilder;

    /**
     * @var string
     */
    protected $firstParamName = 'open';

    /**
     * @var string
     */
    protected $secondParamName = 'closed';

    /**
     * @param PaginatorInterface $paginatorInterface
     * @param RequestStack       $requestStack
     */
    public function __construct(PaginatorInterface $paginatorInterface, RequestStack $requestStack)
    {
        $this->paginator = $paginatorInterface;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param              $page          | page og the pagination
     * @param              $items         | items by page
     * @param              $parameterName | parameter name to route
     * @param              $routeName     | name of the route to use in paginator links
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPagination(QueryBuilder $queryBuilder, $page, $parameterName, $routeName)
    {
        $result = $this->paginator->paginate(
            $queryBuilder,
            $page,
            $this->itemsByPage,
            array(
                'pageParameterName' => $parameterName,
            )
        );
        $result->setUsedRoute($routeName);

        return $result;
    }

    /**
     * @return array
     */
    public function getDoublePagination()
    {
        $first = $this->getPagination(
            $this->firstPaginationQueryBuilder,
            $this->firstPaginationPage,
            $this->firstParamName,
            $this->firstPaginationRoute
        );
        $second = $this->getPagination(
            $this->secondPaginationQueryBuilder,
            $this->secondPaginationPage,
            $this->secondParamName,
            $this->secondPaginationRoute
        );

        $isOpenTab = true;

        if ($this->secondPaginationRoute=== $this->request->get('_route')) {
            $isOpenTab = false;
        }

        return array($first, $second, $isOpenTab);
    }

    /**
     * @param int $itemsByPage
     */
    public function setItemsByPage($itemsByPage)
    {
        $this->itemsByPage = $itemsByPage;

        return $this;
    }

    /**
     * @param string $firstPaginationRoute
     *
     * @return PaginationManager
     */
    public function setFirstPaginationRoute($firstPaginationRoute)
    {
        $this->firstPaginationRoute = $firstPaginationRoute;

        return $this;
    }

    /**
     * @param string $secondPaginationRoute
     *
     * @return PaginationManager
     */
    public function setSecondPaginationRoute($secondPaginationRoute)
    {
        $this->secondPaginationRoute = $secondPaginationRoute;

        return $this;
    }

    /**
     * @param int $firstPaginationPage
     *
     * @return PaginationManager
     */
    public function setFirstPaginationPage($firstPaginationPage)
    {
        $this->firstPaginationPage = $firstPaginationPage;

        return $this;
    }

    /**
     * @param int $secondPaginationPage
     *
     * @return PaginationManager
     */
    public function setSecondPaginationPage($secondPaginationPage)
    {
        $this->secondPaginationPage = $secondPaginationPage;

        return $this;
    }

    /**
     * @param QueryBuilder $firstPaginationQueryBuilder
     *
     * @return PaginationManager
     */
    public function setFirstPaginationQueryBuilder($firstPaginationQueryBuilder)
    {
        $this->firstPaginationQueryBuilder = $firstPaginationQueryBuilder;

        return $this;
    }

    /**
     * @param QueryBuilder $secondPaginationQueryBuilder
     *
     * @return PaginationManager
     */
    public function setSecondPaginationQueryBuilder($secondPaginationQueryBuilder)
    {
        $this->secondPaginationQueryBuilder = $secondPaginationQueryBuilder;

        return $this;
    }

    /**
     * @param string $secondParamName
     *
     * @return PaginationManager
     */
    public function setSecondParamName($secondParamName)
    {
        $this->secondParamName = $secondParamName;

        return $this;
    }

    /**
     * @param string $firstParamName
     *
     * @return PaginationManager
     */
    public function setFirstParamName($firstParamName)
    {
        $this->firstParamName = $firstParamName;

        return $this;
    }
}
