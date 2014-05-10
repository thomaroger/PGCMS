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
    }
}
