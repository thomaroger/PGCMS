<?php

namespace PlaygroundCMS\Blocks;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use PlaygroundCMS\Pager\CMSPager;

abstract class AbstractListController extends AbstractBlockController
{
    protected function getResults($query)
    {
        return $query->getQuery()->getResult();
    }
    
    protected function addSort($mapper, $query)
    {
        $block = $this->getBlock();
        $sortBlockParam = $block->getParam('sort', array());

        if (empty($sortBlockParam)) {
            return $query;
        }

        $supportedSorts = $mapper->getSupportedSorts();

        if (empty($supportedSorts[$sortBlockParam['field']])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown sort "%s" for query, it has to be defined in method %s::getSupportedSorts().',
                $sortAlias,
                get_class($mapper)
            ));
        }

        $sortAlias = $sortBlockParam['field'];
        $sortDirection = !empty($sortBlockParam['direction']) ? $sortBlockParam['direction'] : \Criteria::ASC;

        $modelSortedField = $supportedSorts[$sortAlias];

        return $query->orderBy($modelSortedField, $sortDirection);
    }

    protected function addFilters($mapper, $query)
    {
        $filtersCount = 0;
        $block = $this->getBlock();

        $filtersBlockParam = $block->getParam('filters', array());   
        
        if (empty($filtersBlockParam)) {
            return $query;
        }

        $supportedFilters = $mapper->getSupportedFilters();

        foreach ($filtersBlockParam as $filter => $value) {
            if(!empty($supportedFilters[$filter])){
                $filterMethod = $supportedFilters[$filter];
                if (!method_exists($mapper, $filterMethod)) {
                    throw new \RuntimeException(sprintf(
                        'Every filters\' methods have to be defined in query class, %s::%s() is missing.',
                        get_class($mapper),
                        $filterMethod
                    ));   
                }
                $query = $mapper->$filterMethod($query, $value);
                $filtersCount ++;
            }
        }

        return $query;
    }

    protected function addPager($query)
    {
        $block = $this->getBlock();
        $pagerBlockParam = $block->getParam('pagination', array());
        
        if (empty($pagerBlockParam)) {
            $results = $this->getResults($query);
            return array($results, count($results));
        }

        $pagerOptions = $this->buildParamsPager($pagerBlockParam);

        $query->setMaxResults($pagerOptions['limit']);

        $query = $this->getResults($query);

        $paginator = new Paginator(new ArrayAdapter($query));
        $paginator->setItemCountPerPage($pagerOptions['max_per_page']);
        $paginator->setCurrentPageNumber($pagerOptions['page']);

        return array($paginator, $paginator->getTotalItemCount());
    }

    protected function buildParamsPager($paginationParam)
    {
        $pagerOptions = array();
        $page = 1;

        $limit      = !empty($paginationParam['limit'])        ? $paginationParam['limit']        : null;
        $maxPerPage = !empty($paginationParam['max_per_page']) ? $paginationParam['max_per_page'] : null;

        list($limit, $maxPerPage) = $this->initPagerVars($limit, $maxPerPage);

        if ($maxPerPage > $limit) {
            $maxPerPage = $limit;
        }

        $page = $this->getRequest()->getQuery('page', $page);

        return array(
            'page'         => $page,
            'max_per_page' => $maxPerPage,
            'limit'        => $limit
        );
    }

    private function initPagerVars($limit, $maxPerPage)
    {
        if (null === $maxPerPage) {
            $limit = null !== $limit ? min($limit, CMSPager::DEFAULT_LIMIT) : CMSPager::DEFAULT_LIMIT;
            $maxPerPage = $limit;

            return array($limit, $maxPerPage);
        }

        if (null === $limit) {
            $limit = CMSPager::INFINITE_RESULT;
        }

        return array($limit, min($maxPerPage, CMSPager::DEFAULT_MAX_PER_PAGE));
    }
}