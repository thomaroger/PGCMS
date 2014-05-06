<?php

namespace PlaygroundCMS\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\ServiceManager\ServiceManager;

class BlockListForm extends BlockForm
{
    protected $serviceManager;

    public function __construct($name = null, ServiceManager $sm)
    {

        parent::__construct($name, $sm);

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'configuration[filters][column]',
            'options' => array(
                'label' => 'Column',
                'value_options' => $this->getSupportedFilters(),
                'empty_option' => 'Choose a column to filter',
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

        $this->add(array(
            'name' => 'configuration[filters][value]',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Value',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'validator' => array(
                new \Zend\Validator\NotEmpty(),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'configuration[sort][field]',
            'options' => array(
                'label' => 'Column',
                'empty_option' => 'Choose the column to sort',
                'value_options' => $this->getSupportedSorts(),
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

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'configuration[sort][direction]',
            'options' => array(
                'label' => 'Value',
                'empty_option' => 'Choose a direction',
                'value_options' => $this->getDirection(),
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

        $this->add(array(
            'name' => 'configuration[pagination][max_per_page]',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Max per page',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'validator' => array(
                new \Zend\Validator\NotEmpty(),
            )
        ));

        $this->add(array(
            'name' => 'configuration[pagination][limit]',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Limit',
                'label_attributes' => array(
                    'class'  => 'control-label'
                ),
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'validator' => array(
                new \Zend\Validator\NotEmpty(),
            )
        ));
    }    


    public function getSupportedFilters() 
    {
        $filtersArray = array();
        $filters = array_keys($this->getServiceManager()->get('playgroundcms_block_mapper')->getSupportedFilters());

        foreach ($filters as $filter) {
            $filtersArray[$filter] = $filter;
        }
        
        return $filtersArray;
    }

    public function getSupportedSorts() 
    {
        $sortsArray = array();
        $sorts = array_keys($this->getServiceManager()->get('playgroundcms_block_mapper')->getSupportedSorts());
        foreach ($sorts as $sort) {
            $sortsArray[$sort] = $sort;
        }

        return $sortsArray;
    }



    public function getConfiguration()
    {
        return array('configuration[filters][column]',
            'configuration[filters][value]',
            'configuration[sort][field]',
            'configuration[sort][direction]',
            'configuration[pagination][max_per_page]',
            'configuration[pagination][limit]');
    }

    public function decorateSpecificDecoration($data)
    {

        $configuration = array();

        $data['configuration']['filters'] = array($data['configuration']['filters']['column'] => $data['configuration']['filters']['value']);

        if (!empty($data['configuration']['filters']['column']) && !empty($data['configuration']['filters']['value'])) {
            $configuration['filters'] = array($data['configuration']['filters']['column'] => $data['configuration']['filters']['value']);
        }

        if (!empty($data['configuration']['sort']['field']) && !empty($data['configuration']['sort']['direction'])) {
            $configuration['sort'] = $data['configuration']['sort'];
        }

        $configuration['pagination'] = $data['configuration']['pagination'];
        $data['configuration'] = $configuration;

        return $data;
    }
}
