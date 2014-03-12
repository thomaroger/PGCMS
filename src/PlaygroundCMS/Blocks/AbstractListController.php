<?php

namespace PlaygroundCMS\Blocks;


abstract class BaseListController extends AbstractBlockController
{
    // https://doctrine-orm.readthedocs.org/en/latest/reference/query-builder.html?highlight=orderBy
    protected function addSort($entity, $query, $block)
    {
        $sortBlockParam = $block->getParam('sort', array());

        if (empty($sortBlockParam['field'])) {
            return $query;
        }

        $supportedSorts = $entity->getSupportedSorts();

        if (empty($supportedSorts[$sortBlockParam['field']])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown sort "%s" for query, it has to be defined in method %s::getSupportedSorts().',
                $sortAlias,
                get_class($query)
            ));
        }

        $sortAlias = $sortBlockParam['field'];
        $sortDirection = !empty($sortBlockParam['direction']) ? $sortBlockParam['direction'] : \Criteria::ASC;

        $modelSortedField = $supportedSorts[$sortAlias];

        return $query->orderBy($modelSortedField, $sortDirection);
    }

    protected function addFilters($entity, $query, $block)
    {
        $filtersCount = 0;
        $sortBlockParam = $block->getParam('filters', array());   

        if (empty($sortBlockParam['filters'])) {
            return $query;
        }

        $supportedfilters = $entity->getSupportedFilters();

        foreach ($supportedfilters as $filter => $value) {
            $filterMethod = $supportedFilters[$filter];
            if (!method_exists($entity, $filterMethod)) {
                throw new \RuntimeException(sprintf(
                    'Every filters\' methods have to be defined in query class, %s::%s() is missing.',
                    get_class($entity),
                    $filterMethod
                ));
            }

            $query = $entity->$filterMethod($query, $value);
            $filtersCount ++;
        }

        return $query;
    }

    protected function addPagers($entity, $query, $block)
    {
        $pagerBlockParam = $block->getParam('pagination', array());
        
        if (empty($pagerBlockParam)) {
           return query;
        }

        $optionsPager = $this->buildParamsPager($pagerBlockParam);

        /*
        ->setMaxPerPage($pagerOptions['max_per_page'])
            ->setMaxResults($pagerOptions['limit'])
            ->setCurrentPage($pagerOptions['page']);
            */
    }

    protected function buildParamsPager($paginationParam)
    {
        $pagerOptions = array();

        $limit      = !empty($paginationParam['limit'])        ? $paginationParam['limit']        : null;
        $maxPerPage = !empty($paginationParam['max_per_page']) ? $paginationParam['max_per_page'] : null;

        list($limit, $maxPerPage) = $this->initPagerVars($limit, $maxPerPage);

        if ($maxPerPage > $limit) {
            $maxPerPage = $limit;
        }

        return array(
            'page'         => 0,
            'max_per_page' => $maxPerPage,
            'limit'        => $limit
        );
    }

    private function initPagerVars($limit, $maxPerPage)
    {
        if (null === $maxPerPage) {
            $limit = null !== $limit ? min($limit, CmsPager::DEFAULT_LIMIT) : CmsPager::DEFAULT_LIMIT;
            $maxPerPage = $limit;

            return array($limit, $maxPerPage);
        }

        if (null === $limit) {
            $limit = self::INFINITE_RESULT;
        }

        return array($limit, min($maxPerPage, CmsPager::DEFAULT_MAX_PER_PAGE));
    }
}