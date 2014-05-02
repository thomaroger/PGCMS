<?php

namespace PlaygroundCMS\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\ServiceManager\ServiceManager;

class FreeHTMLForm extends BlockForm
{
    protected $serviceManager;

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
                'required' => 'required',
                'class' => 'form-control',
            )
        ));
    }

    public function getConfiguration()
    {
        return array('configuration[html]');
    }
}
