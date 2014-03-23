<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 21/03/2013
*
* Classe qui permet de surcharger le router afin de posseder l'entité courante (page ou autre model)
**/
namespace PlaygroundCMS\Router;

use Traversable;
use Zend\Mvc\Router\Exception;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteMatch;
use PlaygroundCMS\Cache\Ressources;
use PlaygroundCMS\Service\Ressource;

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

        $this->defaults['ressource'] = $this->getRessource($path);

        return new RouteMatch(array_merge($this->defaults, $matches), $matchedLength);
    }


    public function getRessource($path)
    {
        /**
        $ressoucesCached = new Ressources();
        $ressoucesCached->setRessourceService(new Ressource());
        $ressoucesCached->findRessourceByUrl($path);
        */

        return "";
    }

}
