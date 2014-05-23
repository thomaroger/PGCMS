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

class FreeHTMLForm extends BlockForm
{
    /**
    * {@inheritdoc}
    * __construct : Ajout des champs spécifique au bloc free HTML
    */
    public function __construct($name = null, ServiceManager $sm)
    {

        parent::__construct($name, $sm);

        $this->add(array(
            'name' => 'configuration[html]',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'HTML',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'class' => 'form-control textarea',
                'rows' => 6,
            )
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
        $templates = $this->getServiceManager()->get('playgroundcms_template_service')->getTemplateMapper()->findBy(array('isSystem' => 0, 'blockType' => 'PlaygroundCMS\Blocks\FreeHTMLController'));
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

        return array('configuration[html]');
    }

    /**
    * {@inheritdoc}
    * setDate : Setter des données spécifique du block dans le form
    */
    public function setData($data)
    {
        parent::setData($data);
        
        if (!is_array($data)) {
            $this->get('configuration[html]')->setValue($data->getParam('html'));
        } else {
            if (!empty($data['configuration']['html'])) {
                $this->get('configuration[html]')->setValue($data['configuration']['html']);
            }
        }
    }
}
