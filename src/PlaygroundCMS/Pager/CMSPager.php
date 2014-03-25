<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de donner des valeurs par défaut pour la pagination
**/

namespace PlaygroundCMS\Pager;

class CMSPager
{
    /**
     * @var integer $DEFAULT_PAGE : Page par défaut
     */
    const DEFAULT_PAGE         = 1;
    /**
     * @var integer DEFAULT_MAX_PER_PAGE : Nombre d'item par page
     */
    const DEFAULT_MAX_PER_PAGE = 100;
    /**
     * @var integer DEFAULT_LIMIT : Nombre d'item par defaut par page
     */
    const DEFAULT_LIMIT        = 100;
    /**
     * @var integer INFINITE_RESULT : Nombre d'item par defaut
     */
    const INFINITE_RESULT      = 9999;
}
