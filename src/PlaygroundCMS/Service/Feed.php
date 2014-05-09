<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 12/04/2014
*
* Classe de service pour les feeds
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;

class Feed extends EventProvider implements ServiceManagerAwareInterface
{
    /**
     * @var PlaygroundCMS\Mapper\BlockLayoutZone blockLayoutZoneMapper
     */
    protected $userService;
    
    /**
     * @var PlaygroundCMS\Mapper\BlockLayoutZone blockLayoutZoneMapper
     */
    protected $blockMapper;
    
    /**
     * @var PlaygroundCMS\Mapper\BlockLayoutZone blockLayoutZoneMapper
     */
    protected $pageMapper;
    
    /**
     * @var PlaygroundCMS\Mapper\BlockLayoutZone blockLayoutZoneMapper
     */
    protected $serviceManager;
    
    /**
     * @var PlaygroundCMS\Mapper\BlockLayoutZone blockLayoutZoneMapper
     */
    protected $layoutMapper;
    
    /**
     * @var PlaygroundCMS\Mapper\BlockLayoutZone blockLayoutZoneMapper
     */
    protected $zoneMapper;
    
    /**
     * @var PlaygroundCMS\Mapper\BlockLayoutZone blockLayoutZoneMapper
     */
    protected $templateMapper;


    /**
     * getFeeds : Permet de recuperer les feeds
     *
     * @return array $feeds
    */
    public function getFeeds()
    {
        list($blocks, $users, $pages, $layouts, $zones, $templates) = $this->getDataForFeeds();
        $feeds = array();
        foreach ($blocks as $block) {
            $feeds[$block->getCreatedAt()->getTimestamp().''.$block->getId().'b'] = $block;
        }
        foreach ($users as $user) {
            $feeds[$user->getCreatedAt()->getTimestamp().''.$user->getId().'u'] = $user;
        }
        foreach ($pages as $page) {
            $feeds[$page->getCreatedAt()->getTimestamp().''.$page->getId().'p'] = $page;
        }
        foreach ($layouts as $layout) {
            $feeds[$layout->getCreatedAt()->getTimestamp().''.$layout->getId().'l'] = $layout;
        }
        foreach ($zones as $zone) {
            $feeds[$zone->getCreatedAt()->getTimestamp().''.$zone->getId().'z'] = $zone;
        }
        foreach ($templates as $template) {
            $feeds[$template->getCreatedAt()->getTimestamp().''.$template->getId().'t'] = $template;
        }

        krsort($feeds);
        
        return $feeds;
    }

    /**
    * getDataForFeeds : Permet de recuperer les datas pour les feeds
    *
    * @return array $feeds
    */
    public function getDataForFeeds()
    {
        $data = array();
        $data[] = $this->getBlockMapper()->findAll();
        $data[] = $this->getPageMapper()->findAll();
        $data[] = $this->getUserService()->findAll();
        $data[] = $this->getLayoutMapper()->findAll();
        $data[] = $this->getZoneMapper()->findAll();
        $data[] = $this->getTemplateMapper()->findAll();

        return $data;
    }

    /**
     * getBlockMapper : Getter pour Block
     *
     * @return PlaygroundCMS\Mapper\Block $blockMapper
     */
    protected function getBlockMapper()
    {
        if (empty($this->blockMapper)) {
            $this->setBlockMapper($this->getServiceManager()->get('playgroundcms_block_mapper'));
        }
        
        return $this->blockMapper;
    }

     /**
     * setBlockMapper : Setter pour Block
     * @param PlaygroundCMS\Mapper\Block $blockMapper
     *
     * @return Feed $this
     */
    protected function setBlockMapper($blockMapper)
    {
        $this->blockMapper = $blockMapper;

        return $this;
    }

     /**
     * getPageMapper : Getter pour pageMapper
     *
     * @return PlaygroundCMS\Mapper\Page $pageMapper
     */
    protected function getPageMapper()
    {
        if (empty($this->pageMapper)) {
            $this->setPageMapper($this->getServiceManager()->get('playgroundcms_page_mapper'));
        }

        return $this->pageMapper;
    }

    /**
     * setPageMapper : Setter pour pageMapper
     * @param PlaygroundCMS\Mapper\Page $pageMapper
     *
     * @return Feed $this
     */
    protected function setPageMapper($pageMapper)
    {
        $this->pageMapper = $pageMapper;

        return $this;
    }

     /**
     * getLayoutMapper : Getter pour layoutMapper
     *
     * @return PlaygroundCMS\Mapper\Layout $layoutMapper
     */
    protected function getLayoutMapper()
    {
        if (empty($this->layoutMapper)) {
            $this->setLayoutMapper($this->getServiceManager()->get('playgroundcms_layout_mapper'));
        }

        return $this->layoutMapper;
    }

     /**
     * setLayoutMapper : Setter pour layoutMapper
     * @param PlaygroundCMS\Mapper\Layout $layoutMapper
     *
     * @return Feed $this
     */ 
    protected function setLayoutMapper($layoutMapper)
    {
        $this->layoutMapper = $layoutMapper;

        return $this;
    }

    /**
     * getZoneMapper : Getter pour zoneMapper
     *
     * @return PlaygroundCMS\Mapper\Zone $zoneMapper
     */
    protected function getZoneMapper()
    {
        if (empty($this->zoneMapper)) {
            $this->setZoneMapper($this->getServiceManager()->get('playgroundcms_zone_mapper'));
        }

        return $this->zoneMapper;
    }

     /**
     * setZoneMapper : Setter pour zoneMapper
     * @param PlaygroundCMS\Mapper\Zone $zoneMapper
     *
     * @return Feed $this
     */
    protected function setZoneMapper($zoneMapper)
    {
        $this->zoneMapper = $zoneMapper;

        return $this;
    }

     /**
     * getTemplateMapper : Getter pour templateMapper
     *
     * @return PlaygroundCMS\Mapper\Template $templateMapper
     */
    protected function getTemplateMapper()
    {
        if (empty($this->templateMapper)) {
            $this->setTemplateMapper($this->getServiceManager()->get('playgroundcms_template_mapper'));
        }

        return $this->templateMapper;
    }

     /**
     * setTemplateMapper : Setter pour templateMapper
     * @param PlaygroundCMS\Mapper\Template $templateMapper
     *
     * @return Feed $this
     */
    protected function setTemplateMapper($templateMapper)
    {
        $this->templateMapper = $templateMapper;

        return $this;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserService()
    {
        if (null === $this->userService) {
            $this->setUserService($this->getServiceManager()->get('playgrounduser_user_service'));
        }

        return $this->userService;
    }
    /**
     * setUserMapper
     *
     * @param  UserMapperInterface $userMapper
     * @return Feed $this
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;

        return $this;
    }

     /**
     * getServiceManager : Getter pour serviceManager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

     /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return Feed $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}