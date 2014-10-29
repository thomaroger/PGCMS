<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 29/10/2014
*
* Classe qui permet de gérer l'entity Revision
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
 * @ORM\Table(name="cms_revision") 
 */
class Revision implements InputFilterAwareInterface
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
    protected $type;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $object;

    /**
     * @ORM\Column(name="object_id", type="integer", nullable=false)
     */
    protected $objectId = 0 ;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $revision = 0 ;

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
     * @return LayoutZone $layoutZone
     */
    public function setType($type)
    {
        $this->type = (string) $type;
        
        return $this;
    }

    /**
     * getId : Getter pour id
     *
     * @return int $id
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * setId : Setter pour id
     * @param integer $id 
     *
     * @return LayoutZone $layoutZone
     */
    public function setObject($object)
    {
        $this->object = (string) $object;
        
        return $this;
    }

    /**
     * getId : Getter pour id
     *
     * @return int $id
     */
    public function getObject()
    {
        return $this->object;
    }


    /**
     * setId : Setter pour id
     * @param integer $id 
     *
     * @return LayoutZone $layoutZone
     */
    public function setObjectId($objectId)
    {
        $this->objectId = (integer) $objectId;
        
        return $this;
    }

    /**
     * getId : Getter pour id
     *
     * @return int $id
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * setId : Setter pour id
     * @param integer $id 
     *
     * @return LayoutZone $layoutZone
     */
    public function setRevision($revision)
    {
        $this->revision = (integer) $revision;
        
        return $this;
    }

    /**
     * getId : Getter pour id
     *
     * @return int $id
     */
    public function getRevision()
    {
        return $this->revision;
    }
    
    /**
     * setId : Setter pour id
     * @param integer $id 
     *
     * @return LayoutZone $layoutZone
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        
        return $this;
    }


   

     /**
     * setCreatedAt : Setter pour createdAt 
     * @param datetime $createdAt 
     *
     * @return LayoutZone $layoutZone
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
     * @return LayoutZone $layoutZone
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