<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;

abstract class AbstractBlockController
{

    abstract public function renderBlock(Block $block);

    public function renderAction($block, $format, $parameters)
    {
        $this->setHeaders();
        return $this->renderBlock($block);
    }

    public function getTemplate($block)
    {
        $templates = json_decode($block->getTemplateContext(), true);
        
        $template = __DIR__ .'/../../../../../../design/frontend/default/base/'.$templates['web'];

        return $template;
    }   

    protected function setHeaders()
    {
        /*$response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Cache-Control', 'max-age='.'300')
                               ->addHeaderLine('User-Cache-Control', 'max-age='.'300')
                               ->addHeaderLine('Expires', gmdate("D, d M Y H:i:s", time() + 300))
                               ->addHeaderLine('Pragma', 'cache');*/
    }
}
