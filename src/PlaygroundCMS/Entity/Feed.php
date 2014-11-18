<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 07/11/2014
*
* Classe qui permet de gérer l'entity Feed
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
 * @ORM\Table(name="cms_feed")
 */
class Feed implements InputFilterAwareInterface
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
    protected $model;

    /**
     * @ORM\Column(name="record_id", type="integer", length=255, nullable=false)
     */
    protected $recordId;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $text;

  
    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundUser\Entity\User", inversedBy="user")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    protected $user;

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
     * @return Feed $feed
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

     /**
     * setModel : Setter pour model
     * @param string $model 
     *
     * @return Feed $feed
     */
    public function setModel($model)
    {
        $this->model = (string) $model;

        return $this;
    }

     /**
     * getModel : Getter pour model
     *
     * @return string $model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * setName : Setter pour name
     * @param string $name 
     *
     * @return Feed $feed
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
     * setRecordId : Setter pour recordId
     * @param integer $recordId 
     *
     * @return Feed $feed
     */
    public function setRecordId($recordId)
    {
        $this->recordId = (integer) $recordId;

        return $this;
    }

    /**
     * getRecordId : Getter pour recordId
     *
     * @return integer $recordId
     */
    public function getRecordId()
    {
        return $this->recordId;
    }


   /**
     * setText : Setter pour text
     * @param string $text 
     *
     * @return Feed $feed
     */
    public function setText($text)
    {
        $this->text = (string) $text;

        return $this;
    }

    /**
     * getText : Getter pour text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }


     /**
     * setUser : Setter pour user
     * @param User $user 
     *
     * @return Feed $feed
     */
    public function setUser($user)
    {
        $this->user = (string) $user;

        return $this;
    }

    /**
     * getUser : Getter pour user
     *
     * @return User $locale
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * setCreatedAt : Setter pour createdAt 
     * @param datetime $createdAt 
     *
     * @return Feed $feed
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
     * @return Feed $feed
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