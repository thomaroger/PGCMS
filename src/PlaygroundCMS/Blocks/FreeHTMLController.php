<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use Zend\View\Model\ViewModel;

class FreeHTMLController extends AbstractBlockController
{
   
    public function renderBlock()
    {
        $block = $this->getBlock();
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
        return 'Block HTML';
    }
}
