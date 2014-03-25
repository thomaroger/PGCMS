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
    const SECURITY_ANONYMOUS             = 'SECURITY_ANONYMOUS';
    const SECURITY_UNAUTHENTICATED       = 'SECURITY_UNAUTHENTICATED';
    const SECURITY_AUTHENTICATED         = 'SECURITY_AUTHENTICATED';
    const SECURITY_ACTIVATED_SUSCRIBER   = 'SECURITY_ACTIVATED_SUSCRIBER';
    const SECURITY_UNACTIVATED_SUSCRIBER = 'SECURITY_UNACTIVATED_SUSCRIBER';
}
