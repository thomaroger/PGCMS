<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class BlockListController extends AbstractBlockController
{
   
    public function renderBlock(Block $block)
    {

        $params = array('block' => $block);

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
}
