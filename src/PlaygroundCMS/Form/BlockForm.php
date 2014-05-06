<?php

namespace PlaygroundCMS\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\ServiceManager\ServiceManager;

class BlockForm extends ProvidesEventsForm
{
    protected $serviceManager;

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
                'label' => 'Url export block',
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
                     '0' => 'Non',
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
                     '0' => 'Non',
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

    public function getDirection()
    {
        return array('DESC' => 'DESC',
                     'ASC' => 'ASC');
    }

    public function getBlocksType()
    {
        $types = array();
        $blockTypes = $this->getServiceManager()->get('playgroundcms_block_service')->getBlocksType();
        foreach ($blockTypes as $blockType) {

            $types[$blockType] = $blockType;
        }

        return $types;
    }

    public function getTemplates()
    {
        $templatesFiles = array();
        $templates = $this->getServiceManager()->get('playgroundcms_template_service')->getTemplateMapper()->findBy(array('isSystem' => 0));
        foreach ($templates as $template) {
            $templatesFiles[$template->getFile()] = $template->getFile();
        }

        return $templatesFiles;
    }


    public function setData($data){

        if (!empty($data['name'])) {
            $this->get('name')->setValue($data['name']);
        }

        if (!empty($data['is_exportable'])) {
            $this->get('is_exportable')->setValue(array($data['is_exportable']));
        }

        if (!empty($data['is_gallery'])) {
            $this->get('is_gallery')->setValue(array($data['is_gallery']));
        }
    }

    public function decorateSpecificDecoration($data)
    {
        return $data;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;

        return $this;
    }
}
