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
            $this->get('configuration[name]')->setValue($data->getParam('name'));
        } else {
            if (!empty($data['configuration']['name'])) {
                $this->get('configuration[name]')->setValue($data['configuration']['name']);
            }
        }
    }
}
