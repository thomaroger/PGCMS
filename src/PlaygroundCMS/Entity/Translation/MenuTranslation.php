<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/07/2014
*
* Classe qui permet de gérer la partie I18n de l'entity menu
**/

namespace PlaygroundCMS\Entity\Translation;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping\Entity;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * Gedmo\Translatable\Entity\Translation
 *
 * @Table(
 *         name="cms_menu_translations",
 *         indexes={@index(name="menu_translations_lookup_idx", columns={
 *             "locale", "object_class", "foreign_key"
 *         })},
 *         uniqueConstraints={@UniqueConstraint(name="menu_translations_lookup_unique_idx", columns={
 *             "locale", "object_class", "field", "foreign_key"
 *         })}
 * )
 * @Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class MenuTranslation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}