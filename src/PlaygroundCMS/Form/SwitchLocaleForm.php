<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 02/05/2014
*
* Classe qui permet de gerer les forms de block free HTML
**/
namespace PlaygroundCMS\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\ServiceManager\ServiceManager;

class SwitchLocaleForm extends BlockForm
{
    /**
    * {@inheritdoc}
    */
    public function __construct($name = null, ServiceManager $sm)
    {
        parent::__construct($name, $sm);
    }

    /**
    * getTemplates : Recuperation des templates
    *
    * @return array $templates
    */
    protected function getTemplates()
    {
        $templatesFiles = array();
        $templates = $this->getServiceManager()->get('playgroundcms_template_service')->getTemplateMapper()->findBy(array('isSystem' => 0, 'blockType' => 'PlaygroundCMS\Blocks\SwitchLocaleController'));
        foreach ($templates as $template) {
            $templatesFiles[$template->getFile()] = $template->getFile();
        }

        return $templatesFiles;
    } 
}
