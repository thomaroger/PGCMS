<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 25/03/2014
*
* Classe de service pour l'entite Page
**/
namespace PlaygroundCMS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use ZfcBase\EventManager\EventProvider;
use Datetime;
use PlaygroundCMS\Mapper\Page as PageMapper;
use PlaygroundCMS\Entity\Page as PageEntity;

class Page extends EventProvider implements ServiceManagerAwareInterface
{

    /**
     * @var PlaygroundCMS\Mapper\Page pageMapper
     */
    protected $pageMapper;

    /**
     * @var Zend\ServiceManager\ServiceManager ServiceManager
     */
    protected $serviceManager;

    protected $localeMapper;
    

    public function create($data)
    {

        $page = new PageEntity();
        $layoutContext = array();

        if ($data['page']['web']['active'] == 1) {
            $page->setIsWeb(1);
            $layoutContext['web'] = $data['page']['web']['layout'];
        }
        if ($data['page']['mobile']['active'] == 1) {
            $page->setIsMobile(1);
            $layoutContext['mobile'] = $data['page']['mobile']['layout'];
        }

        $page->setStatus(PageEntity::PAGE_DRAFT);

        if (!empty($data['page']['status'])) {
            $page->setStatus($data['page']['status']);
        }

        $page->setLayoutContext(json_encode($layoutContext));
        $page->setSecurityContext($data['page']['visibility']);

        $startDate = DateTime::createFromFormat('m/d/Y H:i:s', $data['page']['start_date']['date'].' '.$data['page']['start_date']['time']);
        $page->setStartDate($startDate);
        $endDate = DateTime::createFromFormat('m/d/Y H:i:s', $data['page']['end_start']['date'].' '.$data['page']['end_start']['time']);
        $page->setEndDate($endDate);


        $locales = $this->getLocaleMapper()->findBy(array('active_front' => 1));

        foreach ($locales as $locale) {
            if(!empty($data['page'][$locale->getLocale()])) {
                $page->setTranslatableLocale($locale->getLocale());
                $page->setTitle($data['page'][$locale->getLocale()]['title']);
                $page->setTitleMeta($data['page'][$locale->getLocale()]['title_seo']);
                $page->setKeywordMeta($data['page'][$locale->getLocale()]['keyword_seo']);
                $page->setDescriptionMeta($data['page'][$locale->getLocale()]['description_seo']);

                $page = $this->getPageMapper()->persist($page);
                $page = $this->getPageMapper()->findById($page->getId());
            }
           
        }
        $page->createRessource($this->getPageMapper(), $locales);
    }


    public function edit($data){

        $page = $this->getPageMapper()->findById($data['page']['id']);

        $layoutContext = array();

        if ($data['page']['web']['active'] == 1) {
            $page->setIsWeb(1);
            $layoutContext['web'] = $data['page']['web']['layout'];
        }
        if ($data['page']['mobile']['active'] == 1) {
            $page->setIsMobile(1);
            $layoutContext['mobile'] = $data['page']['mobile']['layout'];
        }

        $page->setStatus(PageEntity::PAGE_DRAFT);

        if (!empty($data['page']['status'])) {
            $page->setStatus($data['page']['status']);
        }

        $page->setLayoutContext(json_encode($layoutContext));
        $page->setSecurityContext($data['page']['visibility']);

        $startDate = DateTime::createFromFormat('m/d/Y H:i:s', $data['page']['start_date']['date'].' '.$data['page']['start_date']['time']);
        $page->setStartDate($startDate);
        $endDate = DateTime::createFromFormat('m/d/Y H:i:s', $data['page']['end_start']['date'].' '.$data['page']['end_start']['time']);
        $page->setEndDate($endDate);


        $locales = $this->getLocaleMapper()->findBy(array('active_front' => 1));
        $cpt = 0;
        foreach ($locales as $locale) {
            if(!empty($data['page'][$locale->getLocale()])) {
                if($cpt == 0) {
                    $page->setTitle($data['page'][$locale->getLocale()]['title']);
                    $page->setTitleMeta($data['page'][$locale->getLocale()]['title_seo']);
                    $page->setKeywordMeta($data['page'][$locale->getLocale()]['keyword_seo']);
                    $page->setDescriptionMeta($data['page'][$locale->getLocale()]['description_seo']);
                    $page = $this->getPageMapper()->update($page);
                }
                $page->setTranslatableLocale($locale->getLocale());
                $page->setTitle($data['page'][$locale->getLocale()]['title']);
                $page->setTitleMeta($data['page'][$locale->getLocale()]['title_seo']);
                $page->setKeywordMeta($data['page'][$locale->getLocale()]['keyword_seo']);
                $page->setDescriptionMeta($data['page'][$locale->getLocale()]['description_seo']);

                $page = $this->getPageMapper()->update($page);
                $page = $this->getPageMapper()->findById($page->getId());
                $cpt ++;
            }
        }

        $page->editRessource($this->getPageMapper(), $locales);
    }

    public function checkPage($data)
    {
        // Valeur par défaut

        $data['page']['status'] = (int) $data['page']['status'];

        if (empty($data['page']['start_date']['time'])) {
            $data['page']['start_date']['time'] = '00:00:00';
        }
        if (empty($data['page']['end_start']['date'])) {
            $data['page']['end_start']['date'] = '12/31/2029';
        }

        if (empty($data['page']['end_start']['time'])) {
            $data['page']['end_start']['time'] = '23:59:59';
        }
        
        // Il faut au moins un titre de renseigner
        $locales = $this->getLocaleMapper()->findBy(array('active_front' => 1));
        $title = false;
        foreach ($locales as $locale) {
            if(!empty($data['page'][$locale->getLocale()])) {
                if(!empty($data['page'][$locale->getLocale()]['title'])){
                    $title = true;
                }
            }
        }
        if(!$title){
            return array('status' => 1, 'message' => 'One of title is required', 'data' => $data);
        }

        // Il faut au moins une plateforme d'activer
        if ($data['page']['web']['active'] == 0 && $data['page']['mobile']['active'] == 0) {
            return array('status' => 1, 'message' => 'One of platform must be activated', 'data' => $data);
        }

        // Si une plateforme est active, alors il faut un layout
        if ($data['page']['web']['active'] == 1 && $data['page']['web']['layout'] == '') {
            return array('status' => 1, 'message' => 'For a activate platform, you must have a layout', 'data' => $data);
        }

        // Si une plateforme est active, alors il faut un layout
        if ($data['page']['mobile']['active'] == 1 && $data['page']['mobile']['layout'] == '') {
            return array('status' => 1, 'message' => 'For a activate platform, you must have a layout', 'data' => $data);
        }

        // Il faut une visibility
        if(empty($data['page']['visibility'])) {
            return array('status' => 1, 'message' => 'Visibility is required', 'data' => $data);  
        }
       
        // Il faut un status
        if ($data['page']['status'] == -1) {
            return array('status' => 1, 'message' => 'The status is required', 'data' => $data);        
        }

        // Il faut une date de debut
        if (empty($data['page']['start_date']['date'])) {
            return array('status' => 1, 'message' => 'The start date is required', 'data' => $data);        
        }


        return array('status' => 0, 'message' => '', 'data' => $data);
    }

    /**
     * getPageMapper : Getter pour pageMapper
     *
     * @return PlaygroundCMS\Mapper\Page $pageMapper
     */
    public function getPageMapper()
    {
        if (null === $this->pageMapper) {
            $this->pageMapper = $this->getServiceManager()->get('playgroundcms_page_mapper');
        }

        return $this->pageMapper;
    }

    public function getLocaleMapper()
    {
        if (null === $this->localeMapper) {
            $this->localeMapper = $this->getServiceManager()->get('playgroundcore_locale_mapper');
        }

        return $this->localeMapper;
    }

     /**
     * setPageMapper : Setter pour le pageMapper
     * @param  PlaygroundCMS\Mapper\Page $pageMapper
     *
     * @return Page
     */
    private function setPageMapper(PageMapper $pageMapper)
    {
        $this->pageMapper = $pageMapper;

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
     * @return Page
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}