<?php

namespace Pequiven\SEIPBundle\Entity\Politic;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Pequiven\SEIPBundle\Model\Politic\WorkStudyCircle as ModelWorkStudyCircle;
use Pequiven\SEIPBundle\Entity\PeriodItemInterface;

/**
 * Estudio de Círculo de Trabajo
 *
 * @ORM\Entity(repositoryClass="Pequiven\SEIPBundle\Repository\Politic\WorkStudyCircleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class WorkStudyCircle extends ModelWorkStudyCircle implements PeriodItemInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * Creado por
     * @var \Pequiven\SEIPBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Pequiven\SEIPBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;
    
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;
    
    /**
     * Referencia del círculo de estudio de trabajo
     * @var string
     * @ORM\Column(name="ref",type="string",length=100,nullable=false)
     */
    private $ref = null;
    
    /**
     * Nombre del círculo de estudio de trabajo
     * @var string
     * @ORM\Column(name="name",type="string",length=100,nullable=false)
     */
    private $name = null;
    
    /**
     * Periodo.
     * 
     * @var \Pequiven\SEIPBundle\Entity\Period
     * @ORM\ManyToOne(targetEntity="Pequiven\SEIPBundle\Entity\Period")
     * @ORM\JoinColumn(nullable=false)
     */
    private $period;
    
    /**
     * Constructor
     */
    public function __construct() {
        
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get CreatedBy
     * @return type
     */
    function getCreatedBy() {
        return $this->createdBy;
    }

    /**
     * Set CreatedBy
     * @param \Pequiven\SEIPBundle\Entity\User $createdBy
     * @return \Pequiven\SEIPBundle\Entity\Politic\WorkStudyCircle
     */
    function setCreatedBy(\Pequiven\SEIPBundle\Entity\User $createdBy) {
        $this->createdBy = $createdBy;
        return $this;
    }
    
    /**
     * Get Ref
     * @return type
     */
    public function getRef() {
        return $this->ref;
    }
    
    /**
     * Set Ref
     * @param type $ref
     */
    public function setRef($ref){
        $this->ref = $ref;
    }
    
    /**
     * Get Name
     * @return type
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set Name
     * @param type $name
     */
    public function setName($name){
        $this->name = $name;
    }

    /**
     * Get period
     *
     * @return \Pequiven\SEIPBundle\Entity\Period 
     */
    public function getPeriod() {
        return $this->period;
    }

    /**
     * Set period
     *
     * @param \Pequiven\SEIPBundle\Entity\Period $period
     * @return ArrangementProgram
     */
    public function setPeriod(\Pequiven\SEIPBundle\Entity\Period $period) {
        $this->period = $period;
    }

}