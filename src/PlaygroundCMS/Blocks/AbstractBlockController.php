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
        $template = $this->getTemplate();

        $renderer = new PhpRenderer();
        $resolver = $this->getServiceManager()->get('playgroundcms_module_options')->getTemplateMapResolver();
        $renderer->setResolver($resolver);
        
        $model->setTemplate($template);

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

        
        $template = $templates['web'];
        

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
