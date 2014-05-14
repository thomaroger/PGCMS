<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer l'entity Zone
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
 * @ORM\Table(name="cms_zone") 
 */
class Zone implements InputFilterAwareInterface
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
     * @ORM\OneToMany(targetEntity="PlaygroundCMS\Entity\LayoutZone", mappedBy="Zone")
     */
    protected $layoutzones;

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
     * @return Zone $zone
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
     * @return Zone $zone
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
     * setLayoutzones : Setter pour layoutzones
     * @param array $layoutzones 
     *
     * @return Zone $zone
     */
    public function setLayoutzones($layoutzones)
    {
        $this->layoutzones = $layoutzones;

        return $this;
    }

    /**
     * addLayoutzone : Setter pour layoutzone
     * @param LayoutZone $layoutzone 
     *
     * @return Zone $zone
     */
    public function addLayoutzone(LayoutZone $layoutzone)
    {
        $this->layoutzones[] = $layoutzone;

        return $this;
    }

    /**
     * getLayoutzones : Getter pour layoutzones
     *
     * @return array $layoutzones
     */
    public function getLayoutzones()
    {
        return $this->layoutzones;
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