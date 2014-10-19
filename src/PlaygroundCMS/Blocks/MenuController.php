<?php

/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 20/10/2014
*
* Classe qui permet de gérer l'affichage d'un bloc de menu
**/

namespace PlaygroundCMS\Blocks;

use Zend\View\Model\ViewModel;

class MEnuController extends AbstractBlockController
{
    /**
    * {@inheritdoc}
    * renderBlock : Rendu du bloc d'un bloc HTML
    */
    protected function renderBlock()
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

    /**
    * __toString : Permet de decrire le bloc
    *
    * @return string $return : Block HTML
    */
    public function __toString()
    {
        
        return 'Block Menu';
    }
}
