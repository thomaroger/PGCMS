<?php

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
}