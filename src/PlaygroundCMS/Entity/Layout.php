<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe qui permet de gérer l'entity Layout
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
 * @ORM\Table(name="cms_layout") 
 */
class Layout implements InputFilterAwareInterface
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="PlaygroundCMS\Entity\LayoutZone", mappedBy="Layout")
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
     * @return Layout $layout
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
     * @return Layout $layout
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
     * @return Layout $layout
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
     * @return Layout $layout
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
     * @return Layout $layout
     */
    public function setImage($image)
    {
        $this->image = $image;

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
     * setLayoutzones : Setter pour layoutzones
     * @param array $layoutzones 
     *
     * @return Layout $layout
     */
    public function setLayoutzones($layoutzones)
    {
        $this->layoutzones = $layoutzones;

        return $this;
    }

     /**
     * addLayoutzone : Ajout d'un layoutzone
     * @param LayoutZone $layoutzone 
     *
     * @return Layout $layout
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
     * setCreatedAt : Setter pour createdAt 
     * @param datetime $createdAt 
     *
     * @return Layout $layout
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
     * setUpdatedAt : Setter pour createdAt 
     * @param datetime $updated_at 
     *
     * @return Layout $layout
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