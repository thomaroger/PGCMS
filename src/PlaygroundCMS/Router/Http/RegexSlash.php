<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 21/03/2014
*
* Classe qui permet de surcharger le router afin de posseder l'entité courante (page ou autre model)
**/
namespace PlaygroundCMS\Router\Http;

use Traversable;
use Zend\Mvc\Router\Exception;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteMatch;
use Doctrine\ORM\EntityManager;
use PlaygroundCMS\Cache\Ressources;

/**
 * Regex route.
 */
class RegexSlash extends \Zend\Mvc\Router\Http\Regex implements \Zend\Mvc\Router\Http\RouteInterface
{

    /**
     * match(): defined by RouteInterface interface.
     *
     * @param  Request $request
     * @param  int $pathOffset
     * @return RouteMatch|null
     */
    public function match(Request $request, $pathOffset = null)
    {
        if (!method_exists($request, 'getUri')) {
            return null;
        }

        $uri  = $request->getUri();
        $path = $uri->getPath();

        if ($pathOffset !== null) {
            $result = preg_match('(\G' . $this->regex . ')', $path, $matches, null, $pathOffset);
        } else {
            $result = preg_match('(^' . $this->regex . '$)', $path, $matches);
        }


        if (!$result) {
            return null;
        }

        $matchedLength = strlen($matches[0]);

        foreach ($matches as $key => $value) {
            if (is_numeric($key) || is_int($key) || $value === '') {
                unset($matches[$key]);
            } else {
                $matches[$key] = rawurldecode($value);
            }
        }

        $ressource = $this->getRessource($path);

        if(empty($ressource)){
            return null;
        }

        $this->defaults['ressource'] =  $ressource;
        $this->defaults['matches'] =  $matches;
        
        return new RouteMatch(array_merge($this->defaults, $matches), $matchedLength);
    }

    /**
    * getRessource : Recuperation d'une ressource via un path / url
    * @param string $path
    *
    * @return Ressource $ressource
    */
    private function getRessource($path)
    {
        $cachedRessources = new Ressources();
        $cachedRessources->setEntityManager($this->getEntityManager());
        
        $ressource = $cachedRessources->findRessourceByUrl($path);

        if (empty($ressource)){
            return null;
        }
        
        return $ressource;
    }

    /**
     * getEntityManager : Getter pour EntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {

        return $this->entityManager;
    }

    /**
     * setEntityManager : Setter pour le entityManager
     * @param  EntityManager $entityManager
     *
     * @return RegexSlash
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        
        return $this;
    }

}
