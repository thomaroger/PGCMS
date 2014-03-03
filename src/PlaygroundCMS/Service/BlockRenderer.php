<?php

namespace PlaygroundCms\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use PlaygroundCMS\Entity\Block;

class BlockRenderer extends EventProvider implements ServiceManagerAwareInterface
{
    protected $block;



    public function setBlock(Block $block)
    {
        $this->block = $block;

        return $this;
    }

    public function render()
    {
        return $this->getRenderAction();
    }


    protected function getBlock()
    {
        return $this->block;
    }

    protected function getRenderAction()
    {
        $block = $this->getBlock();
        $blockResponse = $this->getServiceManager()->get('playgroundcms_blockgenerator_service')->generate($block);
        
        return sprintf('
    <!-- Render block -> %s : %s -->
        %s
    <!-- / Render block -> %s : %s -->
',
            $block->getType(),
            $block->getName(),
            $blockResponse,
            $block->getType(),
            $block->getName()
        );
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