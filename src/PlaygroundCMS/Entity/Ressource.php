<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2013
*
* Classe qui permet de gérer l'entity Ressource
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
 * @ORM\Table(name="cms_ressource",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="ressource_unique_idx", columns={"url"})},
 *      indexes={@ORM\Index(name="ressource_index_idx", columns={"model", "record_id", "locale"})}
 * ) 
 */
class Ressource implements InputFilterAwareInterface
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
    protected $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $model;

    /**
     * @ORM\Column(name="record_id", type="integer", length=255, nullable=false)
     */
    protected $recordId;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    protected $locale;

    /**
     * @ORM\Column(name="security_context", type="string", length=255, nullable=false)
     */
    protected $securityContext;

    /**
     * @ORM\Column(name="layout_context", type="text", nullable=false)
     */
    protected $layoutContext;

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
     * @return Ressource $ressource
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

   /**
     * setUrl : Setter pour url
     * @param string $url 
     *
     * @return Ressource $ressource
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;

        return $this;
    }

    /**
     * getUrl : Getter pour url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

     /**
     * setModel : Setter pour model
     * @param string $model 
     *
     * @return Ressource $ressource
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
     * setRecordId : Setter pour recordId
     * @param integer $recordId 
     *
     * @return Ressource $ressource
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
     * setLocale : Setter pour locale
     * @param string $locale 
     *
     * @return Ressource $ressource
     */
    public function setLocale($locale)
    {
        $this->locale = (string) $locale;

        return $this;
    }

    /**
     * getLocale : Getter pour locale
     *
     * @return string $locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * setSecurityContext : Setter pour securityContext
     * @param string $securityContext 
     *
     * @return Ressource $ressource
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = (string) $securityContext;

        return $this;
    }

    /**
     * getSecurityContext : Getter pour securityContext
     *
     * @return string $securityContext
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

     /**
     * setLayoutContext : Setter pour layoutContext
     * @param string $layoutContext 
     *
     * @return Ressource $ressource
     */
    public function setLayoutContext($layoutContext)
    {
        $this->layoutContext = $layoutContext;

        return $this;
    }

     /**
     * getLayoutContext : Getter pour layoutContext
     *
     * @return string $layoutContext
     */
    public function getLayoutContext()
    {
        return $this->layoutContext;
    }

    /**
     * setCreatedAt : Setter pour createdAt 
     * @param datetime $createdAt 
     *
     * @return Ressource $ressource
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
     * @return Ressource $ressource
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