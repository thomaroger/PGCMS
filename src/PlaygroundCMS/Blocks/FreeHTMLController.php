<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class FreeHTMLController extends AbstractBlockController
{
   
    public function renderBlock(Block $block)
    {

        $template = $this->getTemplate($block);
        $renderer = new PhpRenderer();
        $model = new ViewModel(array('block' => $block));

        $resolver = new Resolver\TemplateMapResolver(array(
            'templateBlock' => $template
        ));

        $renderer->setResolver($resolver);

        $model->setTemplate('templateBlock');
        return $renderer->render($model);
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
