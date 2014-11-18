<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 20/1°/2014
*
* Classe qui permet de gerer les forms de block fmenu
**/
namespace PlaygroundCMS\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\ServiceManager\ServiceManager;

class MenuForm extends BlockForm
{
    /**
    * {@inheritdoc}
    * __construct : Ajout des champs spécifique au bloc free HTML
    */
    public function __construct($name = null, ServiceManager $sm)
    {

        parent::__construct($name, $sm);

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'configuration[menu]',
            'options' => array(
                'label' => 'Menu',
                'value_options' => $this->getMenus(),
                'empty_option' => 'Choose a menu',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'class' => 'selectpicker show-tick form-control',
                'data-live-search' => "true",
                'data-size' => '3',
                'data-width' => "100%",
            ),
        ));
    }

    /**
    * getTemplates : Recuperation des templates
    *
    * @return array $templates
    */
    protected function getTemplates()
    {
        $templatesFiles = array();
        $templates = $this->getServiceManager()->get('playgroundcms_template_service')->getTemplateMapper()->findBy(array('isSystem' => 0, 'blockType' => 'PlaygroundCMS\Blocks\MenuController'));
        foreach ($templates as $template) {
            $templatesFiles[$template->getFile()] = $template->getFile();
        }

        return $templatesFiles;
    }

    /**
    * {@inheritdoc}
    * getConfiguration : Définit les champs spécifiques du bloc
    */
    public function getConfiguration()
    {

        return array('configuration[menu]');
    }

    /**
    * {@inheritdoc}
    * setDate : Setter des données spécifique du block dans le form
    */
    public function setData($data)
    {
        parent::setData($data);
        
        if (!is_array($data)) {
            $this->get('configuration[menu]')->setValue($data->getParam('menu'));
        } else {
            if (!empty($data['configuration']['menu'])) {
                $this->get('configuration[menu]')->setValue($data['configuration']['menu']);
            }
        }
    }


     /**
    * getSupportedFilters : Recuperation des filtres supportés par les blocs
    *
    * @return array $filtersArray
    */
    private function getMenus() 
    {
        $menusArray = array();
        $menus = $this->getServiceManager()->get('playgroundcms_menu_mapper')->findAll();
        foreach ($menus as $menu) {
            $menusArray[$menu->getId()] = $menu->getTitle();
        }
        
        return $menusArray;
    }
}
