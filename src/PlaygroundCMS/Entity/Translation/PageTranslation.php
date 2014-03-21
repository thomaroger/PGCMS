<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe qui permet de gérer la partie I18n de l'entity page
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
 *         name="cms_page_translations",
 *         indexes={@index(name="translations_lookup_idx", columns={
 *             "locale", "object_class", "foreign_key"
 *         })},
 *         uniqueConstraints={@UniqueConstraint(name="lookup_unique_idx", columns={
 *             "locale", "object_class", "field", "foreign_key"
 *         })}
 * )
 * @Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class PageTranslation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}