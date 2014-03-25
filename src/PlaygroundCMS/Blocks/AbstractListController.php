<?php

/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer l'affichage de base d'un bloc de liste
**/

namespace PlaygroundCMS\Blocks;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use PlaygroundCMS\Pager\CMSPager;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractListController extends AbstractBlockController
{
    /**
    * getResults : Recuperation des resultats à partir du query
    * @param QueryBuilder $query : query
    *
    * @return array $results : Resultats 
    */
    protected function getResults(QueryBuilder $query)
    {

        return $query->getQuery()->getResult();
    }
    
    /**
    * addSort : Ajout de sort pour la query
    * @param QueryBuilder $query : query
    *
    * @return QueryBuilder $query : query avec le sort
    */
    protected function addSort(QueryBuilder $query)
    {
        $block = $this->getBlock();
        $sortBlockParam = $block->getParam('sort', array());

        if (empty($sortBlockParam)) {
            return $query;
        }


        $mapper = $this->getBlockMapper();

        if (!method_exists($mapper, "getSupportedSorts")) {
            throw new \RuntimeException(sprintf(
                'getSupportedFilters have to be defined in mapper class, %s::%s() is missing.',
                get_class($mapper),
                "getSupportedSorts"
            ));   
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

        $query = $query->orderBy($modelSortedField, $sortDirection);

        return $query;
    }

    /**
    * addSort : Ajout de filters pour la query
    * @param QueryBuilder $query : query
    *
    * @return QueryBuilder $query : query avec le sort
    */
    protected function addFilters(QueryBuilder $query)
    {
        $filtersCount = 0;
        $block = $this->getBlock();

        $filtersBlockParam = $block->getParam('filters', array());   
        
        if (empty($filtersBlockParam)) {
            return $query;
        }

        $mapper = $this->getBlockMapper();

        if (!method_exists($mapper, "getSupportedFilters")) {
            throw new \RuntimeException(sprintf(
                'getSupportedFilters have to be defined in mapper class, %s::%s() is missing.',
                get_class($mapper),
                "getSupportedFilters"
            ));   
        }

        $supportedFilters = $mapper->getSupportedFilters();

        foreach ($filtersBlockParam as $filter => $value) {
            if(!empty($supportedFilters[$filter])){
                $filterMethod = $supportedFilters[$filter];
                if (!method_exists($mapper, $filterMethod)) {
                    throw new \RuntimeException(sprintf(
                        'Every filters\' methods have to be defined in mapper class, %s::%s() is missing.',
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

    /**
    * addPager : Ajout d'une pagination pour la query
    * @param QueryBuilder $query : query
    *
    * @return array $result with Zend\Paginator\Paginator $paginator and int $totalItemCount
    */
    protected function addPager(QueryBuilder $query)
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

    /**
    * buildParamsPager : Permet d'initialiser les variables lié à la pagination
    * @param array $paginationParam : Tableau concernant les variables de pagination
    *
    * @return array $paginationParam : page correspond à la page en cours, max_per_page correspond au nombre d'item par page, limit correspond au nombre d'item
    */
    private function buildParamsPager(array $paginationParam)
    {
        $pagerOptions = array();
        $page = 1;

        $limit      = !empty($paginationParam['limit'])        ? $paginationParam['limit']        : null;
        $maxPerPage = !empty($paginationParam['max_per_page']) ? $paginationParam['max_per_page'] : null;

        list($limit, $maxPerPage) = $this->initPagerVars((int) $limit, (int) $maxPerPage);

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

    /**
    * initPagerVars : Permet d'initialiser les variables lié à la pagination en fonction des valeurs par défaut
    * @param int $limit : Correspond au nombre d'item
    * @param int $maxPerPage : Nombre d'item par page
    *
    * @return array $paginationParam : limit correspond au nombre d'item, max_per_page correspond au nombre d'item par page
    */
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

    /**
    * setBlockMapper : Setter pour le blokcMapper
    * @var PlaygroundCMS\Mapper\* $mapper : Classe de Mapper relié à l'entité qui est requetée
    *
    * @return AbstractListController $abstractListController
    */
    protected function setBlockMapper($blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }
}