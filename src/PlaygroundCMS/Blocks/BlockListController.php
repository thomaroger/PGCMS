<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use PlaygroundCMS\Mapper\Block as BlockMapper;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class BlockListController extends AbstractBlockController
{
    protected $blockMapper;

    public function renderBlock(Block $block)
    {
        $query = $this->getBlockMapper()->getQueryBuilder();
        $query = $query->select('/** PlaygroundCMS\Blocks\BlockListController :: renderBlock **/ b')->from('PlaygroundCMS\Entity\Block', 'b');

        // addFilters
        // addSort
        // addPager

        $query = $query->getQuery();
        $results = $query->getResult();

        $params = array('block' => $block,
                        'results' => $results);

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
