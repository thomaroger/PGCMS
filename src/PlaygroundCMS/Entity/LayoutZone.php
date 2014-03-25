<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer l'entity LayoutZone
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
 * @ORM\Table(name="cms_layout_zone") 
 */
class LayoutZone implements InputFilterAwareInterface
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
     * @ORM\ManyToOne(targetEntity="PlaygroundCMS\Entity\Layout", inversedBy="LayoutZone")
     */
    protected $layout;

    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundCMS\Entity\Zone", inversedBy="LayoutZone")
     */
    protected $zone;

    /**
     * @ORM\OneToMany(targetEntity="PlaygroundCMS\Entity\BlockLayoutZone", mappedBy="LayoutZone")
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
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * setLayout : Setter pour layout
     * @param Layout $layout 
     *
     * @return LayoutZone $layoutZone
     */
    public function setLayout(Layout $layout)
    {
        $this->layout = $layout;

        return $this;
    }

     /**
     * getLayout : Getter pour layout
     *
     * @return Layout $id
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * setZone : Setter pour zone
     * @param Zone $zone 
     *
     * @return LayoutZone $layoutZone
     */
    public function setZone(Zone $zone)
    {
        $this->zone = $zone;

        return $this;
    }

     /**
     * getZone : Getter pour zone
     *
     * @return Zone $zoen
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * setblockLayoutZones : Setter pour blockLayoutZones
     * @param array $blockLayoutZones 
     *
     * @return LayoutZone $layoutZone
     */
    public function setblockLayoutZones($blockLayoutZones)
    {
        $this->blockLayoutZones = $blockLayoutZones;

        return $this;
    }

     /**
     * addblockLayoutZone : Ajout d'un blockLayoutZone
     * @param BlockLayoutZone $blockLayoutZones 
     *
     * @return LayoutZone $layoutZone
     */
    public function addblockLayoutZone(BlockLayoutZone $blockLayoutZone)
    {
        $this->blockLayoutZones[] = $blockLayoutZone;

        return $this;
    }

    /**
     * getblockLayoutZones : Getter pour blockLayoutZones
     *
     * @return array $blockLayoutZones
     */
    public function getblockLayoutZones()
    {
        return $this->blockLayoutZones;
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