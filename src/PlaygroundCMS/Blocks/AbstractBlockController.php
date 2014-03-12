<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use Zend\View\Resolver;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;

abstract class AbstractBlockController
{
    protected $block;

    abstract public function renderBlock(Block $block);

    public function __construct(Block $block)
    {
        $this->setBlock($block);
    }

    public function renderAction($block, $format, $parameters)
    {
        $this->setHeaders();

        return $this->renderBlock($block);
    }

    public function getRenderer($model)
    {
        $blockType = $this->getBlock()->getType();
        $template = $this->getTemplate();

        if (!file_exists($template)) {
             throw new \RuntimeException(sprintf('Template not found for "%s" : "%s"',
                $blockType, $template
            ));
        }

        $resolver = new Resolver\TemplateMapResolver(array(
            $blockType => $template
        ));

        $renderer = new PhpRenderer();
        $renderer->setResolver($resolver);
        
        $model->setTemplate($blockType);

        return $renderer;
    }

    public function render(ViewModel $model)
    {
        return $this->getRenderer($model)->render($model);
    }

    public function getTemplate()
    {
        $block = $this->getBlock();
        $templates = json_decode($block->getTemplateContext(), true);

        /** @todo : à refactorer **/
        
        $template = __DIR__ .'/../../../../../../design/frontend/default/base/'.$templates['web'];

        return $template;
    } 

    public function setBlock(Block $block)
    {
        $this->block = $block;

        return $block;
    }  

    public function getBlock()
    {
        return $this->block;
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
