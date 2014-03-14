<?php

namespace PlaygroundCMS\Blocks;

use PlaygroundCMS\Entity\Block;
use Zend\View\Resolver;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;
use PlaygroundCMS\View\HelperPluginManager;

abstract class AbstractBlockController
{
    protected $block;
    protected $serviceManager;
    protected $renderer;


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

    public function setRenderer()
    {
        $renderer = new PhpRenderer();

        $helperPluginManager = new HelperPluginManager();
        $helperPluginManager->setServiceManager($this->getServiceManager());
        $renderer->setHelperPluginManager($helperPluginManager);
        $resolver = $this->getServiceManager()->get('playgroundcms_module_options')->getTemplateMapResolver();
        $renderer->setResolver($resolver);

        $this->renderer = $renderer;

        return $this;
    }

    public function getRenderer($model)
    {
        if($this->renderer === null) {
            $this->setRenderer();
        }

        $template = $this->getTemplate();
        $model->setTemplate($template);

        return $this->renderer;
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

    public function getRequest()
    {
        return $this->getServiceManager()->get('request');
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
