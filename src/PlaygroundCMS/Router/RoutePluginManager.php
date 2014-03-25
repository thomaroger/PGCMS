<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de surcharger le helper de gestion des routes afin de rajouter le service manager
**/

namespace PlaygroundCMS\Router;

use Zend\Mvc\Router\RoutePluginManager as RoutePluginManagerParent;

/**
 * Plugin manager implementation for routes
 *
 * Enforces that routes retrieved are instances of RouteInterface. It overrides
 * createFromInvokable() to call the route's factory method in order to get an
 * instance. The manager is marked to not share by default, in order to allow
 * multiple route instances of the same type.
 */
class RoutePluginManager extends RoutePluginManagerParent
{
    /**
     * Attempt to create an instance via an invokable class.
     *
     * Overrides parent implementation by invoking the route factory,
     * passing $creationOptions as the argument.
     *
     * ARNING : ADD PlaygroundCMS
     * Add call to setServiceManager to acces to ServiceManager if helper add a method setServiceManager
     *
     *
     *
     * @param  string $canonicalName
     * @param  string $requestedName
     *
     * @return null|\stdClass
     *
     * @throws Exception\RuntimeException If resolved class does not exist, or does not implement RouteInterface
     */
    protected function createFromInvokable($canonicalName, $requestedName)
    {
        $invokable = parent::createFromInvokable($canonicalName, $requestedName);

        if (method_exists($invokable, "setEntityManager")) {
            $invokable->setEntityManager($this->getServiceLocator()->get('doctrine.entitymanager.orm_default'));
        }

        return $invokable;
    }

}
