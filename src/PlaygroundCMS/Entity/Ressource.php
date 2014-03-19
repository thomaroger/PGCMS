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
 *      indexes={@ORM\index(name="ressource_index_idx", columns={"model", "record_id", "locale"})}
 * ) 
 */
class Ressource implements InputFilterAwareInterface
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
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $model
     * @return self
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }


    /**
     * @param $recordId
     * @return self
     */
    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecordId()
    {
        return $this->recordId;
    }


    /**
     * @param $locale
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $securityContext
     * @return self
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * @param $layoutContext
     * @return self
     */
    public function setLayoutContext($layoutContext)
    {
        $this->layoutContext = $layoutContext;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLayoutContext()
    {
        return $this->layoutContext;
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