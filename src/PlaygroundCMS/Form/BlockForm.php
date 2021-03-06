<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 02/05/2014
*
* Classe qui permet de gerer les forms de block
**/
namespace PlaygroundCMS\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\ServiceManager\ServiceManager;
use PlaygroundCMS\Entity\Block;
use Doctrine\Common\Collections\Criteria;

class BlockForm extends ProvidesEventsForm
{
    /**
    * @var ServiceManager $serviceManager
    */
    protected $serviceManager;

    /**
    * __construct
    * @param string $name 
    * @param ServiceManager $sm
    */
    public function __construct($name = null, ServiceManager $sm)
    {
        $this->setServiceManager($sm);

        parent::__construct($name);

        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0,
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Name',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
            ),
            'validator' => array(
                new \Zend\Validator\NotEmpty(),
            )
        ));

         $this->add(array(
            'name' => 'slug',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Slug',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => 'true',
            )
        ));

        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Type',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => 'true',
            )
        ));

        $this->add(array(
            'name' => 'export',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Url Export Block',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'disabled' => 'disabled',
            )
        ));



        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'is_exportable',
            'options' => array(
                'label' => 'Exportable ?',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
                'value_options' => array(
                     '0' => 'No',
                     '1' => 'Yes',
                ),
            ),
            'attributes' => array(
                'class' => "icheck form-control",
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'is_gallery',
            'options' => array(
                'label' => 'Gallery ?',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
                'value_options' => array(
                     '0' => 'No',
                     '1' => 'Yes',
                ),
            ),
            'attributes' => array(
                'class' => "icheck form-control",
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'is_entity_detail',
            'options' => array(
                'label' => 'Block Detail of the Entity ?',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
                'value_options' => array(
                     '0' => 'No',
                     '1' => 'Yes',
                ),
            ),
            'attributes' => array(
                'class' => "icheck form-control",
            ),
        ));


         $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'template_context[web]',
            'options' => array(
                'label' => 'Web template',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
                'empty_option' => 'Choose the template',
                'value_options' => $this->getTemplates(),
            ),
            'attributes' => array(
                'class' => 'selectpicker show-tick form-control',
                'data-live-search' => "true",
                'data-size' => '5',
                'data-width' => "100%",
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'template_context[mobile]',
            'options' => array(
                'label' => 'Mobile template',
                'value_options' => $this->getTemplates(),
                'empty_option' => 'Choose the template',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'class' => 'selectpicker show-tick form-control',
                'data-live-search' => "true",
                'data-size' => '5',
                'data-width' => "100%",
            ),
        ));


        $submitElement = new Element\Button('submit');
        $submitElement->setAttributes(array('type'  => 'submit'));

        $this->add($submitElement, array('priority' => -100));
    }

    /**
    * getDirection : Récuperation des sorts de Criteria
    *
    * @return array $directions
    */
    protected function getDirection()
    {
        return array(
            Criteria::ASC => Criteria::ASC,
            Criteria::DESC => Criteria::DESC
            );
    }

    /**
    * getBlocksType : Recuperation des types de blocs
    *
    * @return array $types
    */
    protected function getBlocksType()
    {
        $types = array();
        $blockTypes = $this->getServiceManager()->get('playgroundcms_block_service')->getBlocksType();
        foreach ($blockTypes as $blockType) {

            $types[$blockType] = $blockType;
        }

        return $types;
    }

    /**
    * getTemplates : Recuperation des templates
    *
    * @return array $templates
    */
    protected function getTemplates()
    {
        $templatesFiles = array();
        $templates = $this->getServiceManager()->get('playgroundcms_template_service')->getTemplateMapper()->findBy(array('isSystem' => 0));
        foreach ($templates as $template) {
            $templatesFiles[$template->getFile()] = $template->getFile();
        }

        return $templatesFiles;
    }

    /**
    * setData : Setter des données du block dans le form
    * @param mixed $data 
    */
    public function setData($data)
    {
        if (!is_array($data)) {
            $this->get('name')->setValue($data->getName());
            $this->get('is_exportable')->setValue($data->getIsExportable());
            $this->get('is_gallery')->setValue($data->getIsGallery());
            $this->get('is_entity_detail')->setValue($data->getIsEntityDetail());
            $templateContext = json_decode($data->getTemplateContext(), true);
            if(!empty($templateContext['web']))  {
                $this->get('template_context[web]')->setValue($templateContext['web']);
            }
            if(!empty($templateContext['mobile']))  {
                $this->get('template_context[mobile]')->setValue($templateContext['mobile']);
            }
        } else {
            if (!empty($data['name'])) {
                $this->get('name')->setValue($data['name']);
            }

            if (!empty($data['is_exportable'])) {
                $this->get('is_exportable')->setValue(array($data['is_exportable']));
            }

            if (!empty($data['is_gallery'])) {
                $this->get('is_gallery')->setValue(array($data['is_gallery']));
            }
            if (!empty($data['is_entity_detail'])) {
                $this->get('is_entity_detail')->setValue(array($data['is_entity_detail']));
            }    
        }
        
    }

    /**
    * decorateSpecificConfguration : Modifier les datas pour prendre en compte les configurations spécifiques du bloc 
    * qui ne sont pas perçues depuis le formulaire
    * @param array $data 
    * 
    * @return array $data
    */
    public function decorateSpecificConfguration($data)
    {
        return $data;
    }

    /**
    * getConfiguration : Permet de définir quelle sont les champs spécifiques à la configuration du bloc
    * 
    * @return array $data 
    */
    public function getConfiguration()
    {
        return array();
    }

    /**
     * getServiceManager : Getter pour le serviceManager
     *
     * @return ServiceManager
     */
    protected function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * setServiceManager : Setter pour le serviceManager
     * @param  ServiceManager $serviceManager
     *
     * @return BlockForm
     */
    protected function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
