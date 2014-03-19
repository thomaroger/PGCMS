<?php

/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe qui permet de gérer l'entity block
**/

namespace PlaygroundCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="cms_block") 
 */
class Block implements InputFilterAwareInterface
{
    protected $inputFilter;


    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $configuration;

    /**
     * @ORM\Column(name="is_exportable", type="boolean", nullable=false)
     */
    protected $isExportable = 0;

    /**
     * @ORM\Column(name="is_gallery", type="boolean", nullable=false)
     */
    protected $isGallery = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $slug;

    /**
     * @ORM\Column(name="template_context", type="text", nullable=false)
     */
    protected $templateContext;

    /**
     * @ORM\OneToMany(targetEntity="PlaygroundCMS\Entity\BlockLayoutZone", mappedBy="Block")
     */
    protected $blockLayoutZones;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;


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
     * @param $url
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $model
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param $configuration
     * @return self
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function getParam($name, $default = '')
    {
       $params = json_decode($this->getConfiguration(), true);

       return $this->hasParam($name) ? $params[$name] : $default;
    }

    public function hasParam($name)
    {
        $params = json_decode($this->getConfiguration(), true);

        return isset($params[$name]);
    }


    /**
     * @param $isExportable
     * @return self
     */
    public function setIsExportable($isExportable)
    {
        $this->isExportable = $isExportable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsExportable()
    {
        return $this->isExportable;
    }

    /**
     * @param $isExportable
     * @return self
     */
    public function setIsGallery($isGallery)
    {
        $this->isGallery = $isGallery;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsGallery()
    {
        return $this->isGallery;
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
     * @return mixed
     */
    public function getTemplateContext()
    {
        return $this->templateContext;
    }


    /**
     * @param $templateContext
     * @return self
     */
    public function setTemplateContext($templateContext)
    {
        $this->templateContext = $templateContext;

        return $this;
    }

    /**
     * @param array $blockLayoutZones
     * @return self
     */
    public function setblockLayoutZones($blockLayoutZones)
    {
        $this->blockLayoutZones = $blockLayoutZones;

        return $this;
    }

    /**
     * @param BlockLayoutZones $blockLayoutZones
     * @return self
     */
    public function addblockLayoutZone($blockLayoutZone)
    {
        $this->blockLayoutZones[] = $blockLayoutZone;

        return $this;
    }

    /**
     * @return Array $blockLayoutZones
     */
    public function getblockLayoutZones()
    {
        return $this->blockLayoutZones;
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

    /** @PrePersist */
    public function createChrono()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /** @PreUpdate */
    public function updateChrono()
    {
        $this->updated_at = new \DateTime("now");
    }
}