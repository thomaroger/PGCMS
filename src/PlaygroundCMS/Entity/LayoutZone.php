<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
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
     * @param Layout $layout
     * @return self
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * @return self $layout
     */
    public function getLayout()
    {
        return $this->layout;
    }


    /**
     * @param Zone $zone
     * @return self
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return self $zone
     */
    public function getZone()
    {
        return $this->zone;
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