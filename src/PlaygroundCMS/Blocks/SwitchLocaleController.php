<?php

/**
* @package : PlaygroundCMS\Blocks
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer les langues et les liens des ressources en fonction des différentes langues
**/

namespace PlaygroundCMS\Blocks;

use Zend\View\Model\ViewModel;

class SwitchLocaleController extends AbstractBlockController
{
    /**
    * @var Locale $localeService
    */
    private $localeService;

    /**
    * @var Ressource $ressourceService
    */
    private $ressourceService;
    
    /**
    * {@inheritdoc}
    * renderBlock : Rendu du bloc d'un bloc HTML
    */
    protected function renderBlock()
    {   
        $locales = array();
        $block = $this->getBlock();

        $coreLocales = $this->getLocaleService()->getLocaleMapper()->findAll();
        foreach ($coreLocales as $locale) {
            $countryCode = strtolower(explode('_', $locale->getLocale())[1]);
            $locales[$locale->getLocale()] = array('name'=> $locale->getName(), 'url' => '/', 'countryCode' => $countryCode);
        }

        $ressources = $this->getRessourceService()->getRessourceMapper()->getRessourcesInAllLocales($this->getRessource());

        foreach ($ressources as $ressource) {
            $locales[$ressource->getLocale()]['url'] = $ressource->getUrl();    
        }

        $params = array('block' => $block, 'locales' => $locales);
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
        
        return 'Block HTML';
    }

     /**
     * getLocaleMapper : Getter pour localeMapper
     *
     * @return PlaygroundCore\Mapper\Locale $localeMapper
     */
    public function getLocaleService()
    {
        if (null === $this->localeService) {
            $this->localeService = $this->getServiceManager()->get('playgroundcore_locale_service');
        }

        return $this->localeService;
    }

    /**
    * getRessourceService : Recuperation du service de Ressource
    *
    * @return Ressource $ressourceService 
    */
    private function getRessourceService()
    {
        if (!$this->ressourceService) {
            $this->ressourceService = $this->getServiceManager()->get('playgroundcms_ressource_service');
        }

        return $this->ressourceService;
    }
}
