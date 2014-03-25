<?php
/**
* @package : PlaygroundCMS
* @author : troger
* @since : 18/03/2014
*
* Classe qui permet de gérer l'entity blockLayoutZone
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
 * @ORM\Table(name="cms_block_layout_zone") 
 */
class BlockLayoutZone implements InputFilterAwareInterface
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
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $order = 0;

    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundCMS\Entity\Block", inversedBy="BlockLayoutZone")
     */
    protected $block;

    /**
     * @ORM\ManyToOne(targetEntity="PlaygroundCMS\Entity\LayoutZone", inversedBy="BlockLayoutZone")
     */
    protected $layoutZone;

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
     * @return BlockLayoutZone $blockLayoutZone
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

     /**
     * getOrder : Getter pour order
     *
     * @return order $order
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * setOrder : Setter pour order
     * @param integer $order 
     *
     * @return BlockLayoutZone $blockLayoutZone
     */
    public function setOrder($order)
    {
        $this->order = (int) $order;

        return $this;
    }

    /**
     * setLayoutZone : Setter pour layoutZone
     * @param LayoutZone $layoutZone 
     *
     * @return BlockLayoutZone $blockLayoutZone
     */
    public function setLayoutZone(layoutZone $layoutZone)
    {
        $this->layoutZone = $layoutZone;

        return $this;
    }

    /**
     * getLayoutZone : Getter pour layoutZone
     *
     * @return LayoutZone $layoutZone
     */
    public function getLayoutZone()
    {
        return $this->layoutZone;
    }

     /**
     * setBlock : Setter pour block
     * @param Block $block 
     *
     * @return BlockLayoutZone $blockLayoutZone
     */
    public function setBlock(Block $block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * getBlock : Getter pour block
     *
     * @return Block $block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * setCreatedAt : Setter pour createdAt 
     * @param datetime $createdAt 
     *
     * @return BlockLayoutZone $blockLayoutZone
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
     * @return BlockLayoutZone $blockLayoutZone
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