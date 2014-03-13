<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use PlaygroundCMS\Mapper\Block as BlockMapper;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class BlockListController extends AbstractListController
{
    protected $blockMapper;

    public function renderBlock()
    {
        $block = $this->getBlock();
        
        $query = $this->getBlockMapper()->getQueryBuilder();
        $query = $query->select('b')->from('PlaygroundCMS\Entity\Block', 'b');

        $this->addFilters($this->getBlockMapper(), $query);
        $this->addSort($this->getBlockMapper(), $query);
    
        $results = $this->getResults($query);
        
        $countResults = count($results);
        $results = $this->addPager($results);

        $params = array('block' => $block,
                        'results' => $results,
                        'countResults' => $countResults);

        $model = new ViewModel($params);
        return $this->render($model);
    }

   
    /*protected function setHeaders(Response $response)
    {
        $response->setMaxAge(300);
        $response->setSharedMaxAge(300);
        $response->setPublic();
    }*/

    
    public function __toString()
    {
        return 'Block list';
    }
    
    public function setBlockMapper(BlockMapper $blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }

    public function getBlockMapper()
    {
        if (empty($this->blockMapper)) {
            $this->setBlockMapper($this->getServiceManager()->get('playgroundcms_block_mapper'));
        }

        return $this->blockMapper;
    }
}
