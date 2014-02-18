<?php

namespace PlaygroundCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="cms_page")
 * @Gedmo\TranslationEntity(class="PlaygroundCMS\Entity\PageTranslation")
 */
class Page implements InputFilterAwareInterface
{
    protected $inputFilter;

    protected $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(name="is_web", type="boolean", nullable=false)
     */
    protected $isWeb = 0;

    /**
     * @ORM\Column(name="is_mobile", type="boolean", nullable=false)
     */
    protected $isMobile = 0;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $status = 0;
     
    /**
     * @ORM\Column(name="start_date", type="datetime",nullable=false)
     */
    protected $startDate;

    /**
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    protected $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $slug;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="title_meta", type="string", length=255, nullable=false)
     */
    protected $titleMeta;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="description_meta", type="string", length=255, nullable=false)
     */
    protected $descriptionMeta;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="keyword_meta", type="string", length=255, nullable=false)
     */
    protected $keywordMeta;


    /**
     * Getter for id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Setter for id
     *
     * @param mixed $id Value to set
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Getter for is_web
     *
     * @return mixed
     */
    public function getIsWeb()
    {
        return $this->isWeb;
    }
    
    /**
     * Setter for is_web
     *
     * @param mixed $isWeb Value to set
     * @return self
     */
    public function setIsWeb($isWeb)
    {
        $this->isWeb = $isWeb;
        return $this;
    }

     /**
     * Getter for isMobile
     *
     * @return mixed
     */
    public function getIsMobile()
    {
        return $this->isMobile;
    }
    
    /**
     * Setter for isMobile
     *
     * @param mixed $isMobile Value to set
     * @return self
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;
        return $this;
    }

     /**
     * Getter for status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Setter for status
     *
     * @param mixed $status Value to set
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
    * Getter for startDate
    *
    * @return mixed
    */
    public function getStartDate()
    {    
       return $this->startDate;
    }
   
   /**
    * Setter for startDate
    *
    * @param mixed $startDate Value to set
    * @return self
    */
    public function setStartDate($startDate)
    {
       $this->startDate = $startDate;
       return $this;
    }

    /**
    * Getter for endDate
    *
    * @return mixed
    */
    public function getEndDate()
    {    
       return $this->endDate;
    }
   
   /**
    * Setter for endDate
    *
    * @param mixed $endDate Value to set
    * @return self
    */
    public function setEndDate($endDate)
    {
       $this->endDate = $endDate;
       return $this;
    }

    /**
     * @param $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param $updatedAt
     * @return self
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

     /**
     * @param $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $slug
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $titleMeta
     * @return self
     */
    public function setTitleMeta($slug)
    {
        $this->titleMeta = $titleMeta;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleMeta()
    {
        return $this->titleMeta;
    }


    /**
     * @param $keywordMeta
     * @return self
     */
    public function setKeywordMeta($keywordMeta)
    {
        $this->keywordMeta = $keywordMeta;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeywordMeta()
    {
        return $this->keywordMeta;
    }

    /**
     * @param $descriptionMeta
     * @return self
     */
    public function setDescriptionMeta($descriptionMeta)
    {
        $this->descriptionMeta = $descriptionMeta;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescriptionMeta()
    {
        return $this->descriptionMeta;
    }


    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}