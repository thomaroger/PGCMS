<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer l'entity block
**/
namespace PlaygroundCMS\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use PlaygroundCore\Filter\Slugify;

/**
 * @ORM\Entity @HasLifecycleCallbacks
 * @ORM\Table(name="cms_block") 
 */
class Block implements InputFilterAwareInterface
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
    protected $type;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $configuration;

    /**
     * @ORM\Column(name="is_exportable", type="boolean", nullable=false)
     */
    protected $isExportable = 0;

    /**
     * @ORM\Column(name="is_gallery", type="boolean", nullable=false)
     */
    protected $isGallery = 0;

    /**
     * @ORM\Column(name="is_entity_detail", type="boolean", nullable=false)
     */
    protected $isEntityDetail = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $slug;

    /**
     * @ORM\Column(name="template_context", type="text", nullable=false)
     */
    protected $templateContext;

    /**
     * @ORM\OneToMany(targetEntity="PlaygroundCMS\Entity\BlockLayoutZone", mappedBy="Block")
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
     * @return Block $block
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * setId : Setter pour id
     * @param integer $id 
     *
     * @return Block $block
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
     * @return Block $block
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        $slugify = new Slugify;
        $this->setSlug($slugify->filter($name));

        return $this;
    }

    /**
     * getName : Getter pour name
     * 
     * @return  string $name 
     */
    public function getName()
    {
        return $this->name;
    }

   /**
     * setType : Setter pour type 
     * @param string $type 
     *
     * @return Block $block
     */
    public function setType($type)
    {
        $this->type = (string) $type;

        return $this;
    }

   /**
     * getType : Getter pour type
     * 
     * @return  string $name 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * setConfiguration : Setter pour configuration 
     * @param string $configuration 
     *
     * @return Block $block
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = (string) $configuration;

        return $this;
    }

     /**
     * getConfiguration : Getter pour configuration
     * 
     * @return  string $configuration 
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

     /**
     * setIsExportable : Setter pour isExportable 
     * @param boolean $isExportable 
     *
     * @return Block $block
     */
    public function setIsExportable($isExportable)
    {
        $this->isExportable = (boolean) $isExportable;

        return $this;
    }

   /**
     * getIsExportable : Getter pour isExportable
     * 
     * @return boolean $isExportable 
     */
    public function getIsExportable()
    {
        return $this->isExportable;
    }

    /**
     * setIsGallery : Setter pour isGallery 
     * @param boolean $isGallery 
     *
     * @return Block $block
     */
    public function setIsGallery($isGallery)
    {
        $this->isGallery = (boolean) $isGallery;

        return $this;
    }

    /**
     * getIsGallery : Getter pour isGallery
     * 
     * @return boolean $isGallery 
     */
    public function getIsGallery()
    {
        return $this->isGallery;
    }

     /**
     * setIsEntityDetail : Setter pour isEntityDetail 
     * @param boolean $isEntityDetail 
     *
     * @return Block $block
     */
    public function setIsEntityDetail($isEntityDetail)
    {
        $this->isEntityDetail = (boolean) $isEntityDetail;

        return $this;
    }

   /**
     * getIsEntityDetail : Getter pour isEntityDetail
     * 
     * @return boolean $isEntityDetail 
     */
    public function getIsEntityDetail()
    {
        return $this->isEntityDetail;
    }

    /**
     * setSlug : Setter pour slug 
     * @param string $slug 
     *
     * @return Block $block
     */
    public function setSlug($slug)
    {
        $this->slug = (string) $slug;

        return $this;
    }

    /**
     * getSlug : Getter pour slug
     * 
     * @return string $slug 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * getTemplateContext : Getter pour templateContext
     * 
     * @return string $templateContext 
     */
    public function getTemplateContext()
    {
        return $this->templateContext;
    }

    /**
     * setTemplateContext : Setter pour templateContext 
     * @param string $templateContext 
     *
     * @return Block $block
     */
    public function setTemplateContext($templateContext)
    {
        $this->templateContext = (string) $templateContext;

        return $this;
    }

     /**
     * setblockLayoutZones : Setter pour blockLayoutZones 
     * @param array $blockLayoutZones 
     *
     * @return Block $block
     */
    public function setblockLayoutZones($blockLayoutZones)
    {
        $this->blockLayoutZones = $blockLayoutZones;

        return $this;
    }

    /**
     * addblockLayoutZone : ajout d'un  blockLayoutZone
     * @param BlockLayoutZone $blockLayoutZone 
     *
     * @return Block $block
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
     * @return Block $block
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
     * @return Block $block
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

    /**
    * getParam : Peremet de retourner un param depuis la configuration du block
    * @param string $name : Nom du param à trouver
    * @param string $default : Texte par défaut si pas de param
    *
    * @return mixed $param
    */
    public function getParam($name, $default = '')
    {
       $params = json_decode($this->getConfiguration(), true);

       return $this->hasParam($name) ? $params[$name] : $default;
    }
    
    /**
    * hasParam : Peremet de savoir si le param existe
    * @param string $name : Nom du param à trouver
    *
    * @return boolean $boolean : Savoir si le param existe
    */
    public function hasParam($name)
    {
        $params = json_decode($this->getConfiguration(), true);

        return isset($params[$name]);
    }

    /**
    * getUrlForExport : Recuperation de l'url pour l'export de bloc
    *
    * @return string $url 
    */
    public function getUrlForExport()
    {
        if($this->getIsExportable() == false){
        
            return '';
        }

        return '/fr_fr/export-block/'.$this->getSlug().'-'.$this->getId().'.html';
    }
}