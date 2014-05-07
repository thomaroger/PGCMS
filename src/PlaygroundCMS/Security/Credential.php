<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 21/03/2014
*
* Classe qui permet de gérer les credentials
**/
namespace PlaygroundCMS\Security;

class Credential
{
    /**
    * @var string SECURITY_ANONYMOUS
    */
    const SECURITY_ANONYMOUS             = 'SECURITY_ANONYMOUS';
    
    /**
    * @var string SECURITY_UNAUTHENTICATED
    */
    const SECURITY_UNAUTHENTICATED       = 'SECURITY_UNAUTHENTICATED';
    
    /**
    * @var string SECURITY_AUTHENTICATED
    */
    const SECURITY_AUTHENTICATED         = 'SECURITY_AUTHENTICATED';
    
    /**
    * @var string SECURITY_ACTIVATED_SUSCRIBER
    */
    const SECURITY_ACTIVATED_SUSCRIBER   = 'SECURITY_ACTIVATED_SUSCRIBER';
    
    /**
    * @var string SECURITY_UNACTIVATED_SUSCRIBER
    */
    const SECURITY_UNACTIVATED_SUSCRIBER = 'SECURITY_UNACTIVATED_SUSCRIBER';

    /**
    * @var array statusesForm : liste des status pour prise en compte dans les forms (page)
    */
    public static $statusesForm = array('success' => self::SECURITY_ANONYMOUS,
                                        'warning' => self::SECURITY_UNAUTHENTICATED,
                                        'danger' => self::SECURITY_AUTHENTICATED,
                                        'info' => self::SECURITY_ACTIVATED_SUSCRIBER,
                                        'default' => self::SECURITY_UNACTIVATED_SUSCRIBER);
}
