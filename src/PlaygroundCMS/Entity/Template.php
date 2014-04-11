<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer l'entity Template
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
 * @ORM\Table(name="cms_template") 
 */
class Template implements InputFilterAwareInterface
{
    /** 
    * @var InputFilter $inputFilter
    */
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
    protected $file;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $entity;

    /**
     * @ORM\Column(name="block_type", type="string", length=255, nullable=true)
     */
    protected $blockType;

    /**
     * @ORM\Column(name="is_system", type="boolean", nullable=false)
     */
    protected $isSystem = false;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * getId : Getter pour id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * setId : Setter pour id
     * @param integer $id 
     *
     * @return Template $template
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * setName : Setter pour name
     * @param string $name 
     *
     * @return Template $template
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * getName : Getter pour name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

     /**
     * setFile : Setter pour file
     * @param string $file 
     *
     * @return Template $template
     */
    public function setFile($file)
    {
        $this->file = (string) $file;

        return $this;
    }

   /**
     * getFile : Getter pour file
     *
     * @return string $file
     */
    public function getFile()
    {
        return $this->file;
    }

     /**
     * setDescription : Setter pour description
     * @param string $description 
     *
     * @return Template $template
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * getDescription : Getter pour description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

     /**
     * setImage : Setter pour image
     * @param string $image 
     *
     * @return Template $template
     */
    public function setImage($image)
    {
        $this->image = (string) $image;

        return $this;
    }

     /**
     * getImage : Getter pour image
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
    }

     /**
     * setEntity : Setter pour entity
     * @param string $entity 
     *
     * @return Template $template
     */
    public function setEntity($entity)
    {
        $this->entity = (string) $entity;

        return $this;
    }

    /**
     * getEntity : Getter pour entity
     *
     * @return string $entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * setBlockType : Setter pour blockType
     * @param string $blockType 
     *
     * @return Template $template
     */
    public function setBlockType($blockType)
    {
        $this->blockType = (string) $blockType;

        return $this;
    }

    /**
     * getBlockType : Getter pour blockType
     *
     * @return string $blockType
     */
    public function getBlockType()
    {
        return $this->blockType;
    }


    /**
     * setIsSystem : Setter pour blockType
     * @param string $blockType 
     *
     * @return Template $template
     */
    public function setIsSystem($isSystem)
    {
        $this->isSystem = (boolean) $isSystem;

        return $this;
    }

    /**
     * getIsSystem : Getter pour isSystem
     *
     * @return boolean $isSystem
     */
    public function getIsSystem()
    {
        return $this->isSystem;
    }

    /**
     * setCreatedAt : Setter pour createdAt 
     * @param datetime $createdAt 
     *
     * @return Template $template
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $created_at;

        return $this;
    }

     /**
     * getCreatedAt : Getter pour created_at 
     *
     * @return datetime $created_at 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

     /**
     * setUpdatedAt : Setter pour updated_at 
     * @param datetime $updated_at 
     *
     * @return Template $template
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * getUpdatedAt : Getter pour updated_at 
     *
     * @return datetime $updated_at 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

     /**
     * getArrayCopy : Convertit l'objet en tableau.
     *
     * @return array $array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * populate : Populate l'object à partir d'un array
     * @param array $data
     *
     */
    public function populate($data = array())
    {

    }

     /**
     * setInputFilter : Rajoute des Filtres
     * @param InputFilterInterface $inputFilter
     *
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * getInputFilter : Rajoute des Filtres
     *
     * @return InputFilter $inputFilter
     */
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