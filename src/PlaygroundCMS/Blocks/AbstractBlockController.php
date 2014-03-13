<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use Zend\View\Resolver;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractBlockController
{
    protected $block;
    protected $serviceManager;

    abstract public function renderBlock();

    public function __construct(ServiceManager $serviceManager, Block $block)
    {
        $this->setBlock($block);
        $this->setServiceManager($serviceManager);
    }

    public function renderAction($format, $parameters)
    {
        $this->setHeaders();

        return $this->renderBlock();
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

     /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param  ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
