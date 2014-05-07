<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 02/05/2014
*
* Classe qui permet de gerer les forms de block Block List
**/
namespace PlaygroundCMS\Form;

use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\ServiceManager\ServiceManager;

class BlockListForm extends BlockForm
{
    /**
    * {@inheritdoc}
    * __construct : Ajout des champs spécifique au bloc de liste de bloc
    */
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

    /**
    * getSupportedFilters : Recuperation des filtres supportés par les blocs
    *
    * @return array $filtersArray
    */
    private function getSupportedFilters() 
    {
        $filtersArray = array();
        $filters = array_keys($this->getServiceManager()->get('playgroundcms_block_mapper')->getSupportedFilters());

        foreach ($filters as $filter) {
            $filtersArray[$filter] = $filter;
        }
        
        return $filtersArray;
    }

    /**
    * getSupportedSorts : Recuperation des sorts supportées par les blocs
    *
    * @return array $sortsArray
    */
    private function getSupportedSorts() 
    {
        $sortsArray = array();
        $sorts = array_keys($this->getServiceManager()->get('playgroundcms_block_mapper')->getSupportedSorts());
        foreach ($sorts as $sort) {
            $sortsArray[$sort] = $sort;
        }

        return $sortsArray;
    }

    /**
    * {@inheritdoc}
    * setDate : Setter des données spécifique du block dans le form
    */
    public function setData($data)
    {
        parent::setData($data);

        if (!is_array($data)) {

            $filters = $data->getParam('filters');
            $sort = $data->getParam('sort');
            $pagination = $data->getParam('pagination');
            if(!empty($filters)) {
                $this->get('configuration[filters][column]')->setValue(key($filters));
                $this->get('configuration[filters][value]')->setValue($filters[key($filters)]);
            }
            if(!empty($sort)) {
                $this->get('configuration[sort][field]')->setValue($sort['field']);
                $this->get('configuration[sort][direction]')->setValue($sort['direction']);
            }
            if(!empty($pagination)) {
                $this->get('configuration[pagination][max_per_page]')->setValue($pagination['max_per_page']);
                $this->get('configuration[pagination][limit]')->setValue($pagination['limit']);
            }

        } else {
            if (!empty($data['configuration']['filters']['column'])) {
                $this->get('configuration[filters][column]')->setValue($data['configuration']['filters']['column']);
            }
            if (!empty($data['configuration']['filters']['value'])) {
                $this->get('configuration[filters][value]')->setValue($data['configuration']['filters']['value']);
            }
            if (!empty($data['configuration']['sort']['field'])) {
                $this->get('configuration[sort][field]')->setValue($data['configuration']['sort']['field']);
            }
            if (!empty($data['configuration']['sort']['direction'])) {
                $this->get('configuration[sort][direction]')->setValue($data['configuration']['sort']['direction']);
            }
            if (!empty($data['configuration']['pagination']['max_per_page'])) {
                $this->get('configuration[pagination][max_per_page]')->setValue($data['configuration']['pagination']['max_per_page']);
            }
            if (!empty($data['configuration']['pagination']['limit'])) {
                $this->get('configuration[pagination][limit]')->setValue($data['configuration']['pagination']['limit']);
            }
        }

    }

    /**
    * {@inheritdoc}
    * getConfiguration : Définit les champs spécifiques du bloc
    */
    public function getConfiguration()
    {
        return array('configuration[filters][column]',
            'configuration[filters][value]',
            'configuration[sort][field]',
            'configuration[sort][direction]',
            'configuration[pagination][max_per_page]',
            'configuration[pagination][limit]');
    }

    /**
    * {@inheritdoc}
    * decorateSpecificConfguration : Modifit la configuration du bloc avant mise en base
    */
    public function decorateSpecificConfguration($data)
    {
        $configuration = array();
        
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
