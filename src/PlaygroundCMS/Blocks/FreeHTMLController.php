<?php

/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 18/03/2013
*
* Classe qui permet de gérer l'affichage d'un bloc de type HTML
**/

namespace PlaygroundCMS\Blocks;

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
