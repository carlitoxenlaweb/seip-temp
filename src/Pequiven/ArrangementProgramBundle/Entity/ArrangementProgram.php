<?php

namespace Pequiven\ArrangementProgramBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pequiven\ArrangementProgramBundle\Model\ArrangementProgram as Model;
use Gedmo\Mapping\Annotation as Gedmo;
use Pequiven\SEIPBundle\Entity\Result\ResultItemInterface;
use Pequiven\SEIPBundle\Entity\PeriodItemInterface;

/**
 * Programa de gestion
 *
 * @ORM\Entity(repositoryClass="Pequiven\ArrangementProgramBundle\Repository\ArrangementProgramRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ArrangementProgram extends Model implements \Pequiven\SEIPBundle\Entity\Result\ResultItemInterface, PeriodItemInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Referencia del programa de gestion
     * @var string
     * @ORM\Column(name="ref",type="string",length=100,nullable=false)
     */
    private $ref = null;

    /**
     * Periodo.
     * @var \Pequiven\SEIPBundle\Entity\Period
     *
     * @ORM\ManyToOne(targetEntity="Pequiven\SEIPBundle\Entity\Period")
     * @ORM\JoinColumn(nullable=false)
     */
    private $period;

    /**
     * Objetivo táctico
     * @var \Pequiven\ObjetiveBundle\Entity\Objetive
     *
     * @ORM\ManyToOne(targetEntity="Pequiven\ObjetiveBundle\Entity\Objetive",inversedBy="tacticalArrangementPrograms")
     * @ORM\JoinColumn(name="tactical_objective_id")
     */
    private $tacticalObjective;

    /**
     * Objetivo operativo.
     * @var \Pequiven\ObjetiveBundle\Entity\Objetive
     *
     * @ORM\ManyToOne(targetEntity="Pequiven\ObjetiveBundle\Entity\Objetive",inversedBy="operationalArrangementPrograms")
     * @ORM\JoinColumn(name="operational_objective_id")
     */
    private $operationalObjective;

    /**
     * Proceso
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=255, nullable=true)
     */
    private $process;

    /**
     * Descripcion del programa
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Estatus del programa de gestion
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status = self::STATUS_DRAFT;

    /**
     * Responsables del programa
     * @var \Pequiven\SEIPBundle\Entity\User
     *
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\User",inversedBy="arrangementPrograms")
     */
    protected $responsibles;

    /**
     * Linea de tiempo
     * @var \Pequiven\ArrangementProgramBundle\Entity\Timeline
     *
     * @ORM\OneToOne(targetEntity="Pequiven\ArrangementProgramBundle\Entity\Timeline",inversedBy="arrangementProgram",cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $timeline;

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
     * Detalles del programa de gestion
     * 
     * @var \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Details
     * @ORM\OneToOne(targetEntity="Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Details",cascade={"persist","remove"})
     * @ORM\Joincolumn(nullable=false)
     */
    protected $details;

    /**
     * Historiales 
     * 
     * @var \Pequiven\SEIPBundle\Entity\Historical
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\Historical",cascade={"persist","remove"})
     */
    protected $histories;

    /**
     * Observaciones
     * 
     * @var \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Observation
     * @ORM\OneToMany(targetEntity="Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Observation",mappedBy="arrangementProgram",cascade={"persist","remove"})
     */
    protected $observations;

    /**
     * Avance total del programa
     * @var float
     * @ORM\Column(name="totalAdvance",type="float")
     */
    protected $totalAdvance = 0;

    /**
     * Avance del programa hasta la fecha
     * 
     * @var integer
     * @ORM\Column(name="progressToDate",type="float")
     */
    protected $progressToDate = 0;

    /**
     * @var \DateTime
     * @ORM\Column(name="lastDateCalculateResult", type="datetime",nullable=true)
     */
    private $lastDateCalculateResult;

    /**
     *
     * @var boolean
     * @ORM\Column(name="isAvailableInResult",type="boolean")
     */
    private $isAvailableInResult = true;

    /**
     * ¿Se puede penalizar el resultado?
     * @var boolean
     * @ORM\Column(name="couldBePenalized",type="boolean")
     */
    private $couldBePenalized = true;

    /**
     * ¿Forzar la penalizacion del resultado?
     * @var boolean
     * @ORM\Column(name="forcePenalize",type="boolean")
     */
    private $forcePenalize = false;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Resultado original
     * 
     * @var float
     * @ORM\Column(name="resultReal",type="float")
     */
    protected $resultReal = 0;

    /**
     * Resultado REAL DEL PROGRAMA
     * 
     * @var float
     * @ORM\Column(name="RealResultAP",type="float")
     */
    protected $realResult = 0;

    /**
     * ¿El resultado que irá a evaluaciones, será colocado manualmente? Quiere decir que de acuerdo a previa solicitud y justificación se puede editar el resultado del programa de gestión.
     * 
     * @var boolean
     * @ORM\Column(name="updateResultByAdmin",type="boolean")
     */
    protected $updateResultByAdmin = false;

    /**
     * Resultado modificado
     * 
     * @var float
     * @ORM\Column(name="resultModified",type="float")
     */
    protected $resultModified = 0;

    /**
     * Penalización
     * 
     * @var float
     * @ORM\Column(name="penalty",type="float")
     */
    protected $penalty = 0;

    /**
     * Resultado antes de la Penalización
     * 
     * @var float
     * @ORM\Column(name="resultbeforepenalty",type="float")
     */
    protected $resultBeforepenalty = 0;

    /**
     * ¿Mostar navegación al informe de evolución?
     * @var boolean
     * @ORM\Column(name="showEvolutionView",type="boolean")
     */
    private $showEvolutionView = false;

    /**
     * Analisis de Tendencia del Programa
     * 
     * @var \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionTrend
     * @ORM\OneToMany(targetEntity="Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionTrend",mappedBy="arrangementProgram",cascade={"persist","remove"})
     */
    protected $arrangementProgramTrend;

    /**
     * Analisis de Tendencia del Programa
     * 
     * @var \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCause
     * @ORM\OneToMany(targetEntity="Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCause",mappedBy="arrangementProgram",cascade={"persist","remove"})
     */
    protected $arrangementProgramCauses;

    /**
     * Analisis de Tendencia del Programa
     * 
     * @var \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCauseAnalysis
     * @ORM\OneToMany(targetEntity="Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCauseAnalysis",mappedBy="arrangementProgram",cascade={"persist","remove"})
     */
    protected $arrangementProgramCausesAnalysis;

    /**
     * Analisis de Tendencia del Programa
     * 
     * @var \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCause
     * @ORM\OneToMany(targetEntity="Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionActionVerification",mappedBy="arrangementProgram",cascade={"persist","remove"})
     */
    protected $arrangementProgramVerification;

    public function __construct() {
        $this->responsibles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->histories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangementProgramTrend = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangementProgramCauses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangementProgramCausesAnalysis = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangementProgramVerification = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set process
     *
     * @param string $process
     * @return ArrangementProgram
     */
    public function setProcess($process) {
        $this->process = $process;

        return $this;
    }

    /**
     * Get process
     *
     * @return string 
     */
    public function getProcess() {
        return $this->process;
    }

    /**
     * Set period
     *
     * @param \Pequiven\SEIPBundle\Entity\Period $period
     * @return ArrangementProgram
     */
    public function setPeriod(\Pequiven\SEIPBundle\Entity\Period $period = null) {
        $this->period = $period;

        return $this;
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
     * Set tacticalObjective
     *
     * @param \Pequiven\ObjetiveBundle\Entity\Objetive $tacticalObjective
     * @return ArrangementProgram
     */
    public function setTacticalObjective(\Pequiven\ObjetiveBundle\Entity\Objetive $tacticalObjective = null) {
        $this->tacticalObjective = $tacticalObjective;

        return $this;
    }

    /**
     * Get tacticalObjective
     *
     * @return \Pequiven\ObjetiveBundle\Entity\Objetive 
     */
    public function getTacticalObjective() {
        return $this->tacticalObjective;
    }

    /**
     * Set operationalObjective
     *
     * @param \Pequiven\ObjetiveBundle\Entity\Objetive $operationalObjective
     * @return ArrangementProgram
     */
    public function setOperationalObjective(\Pequiven\ObjetiveBundle\Entity\Objetive $operationalObjective = null) {
        $this->operationalObjective = $operationalObjective;

        return $this;
    }

    /**
     * Get operationalObjective
     *
     * @return \Pequiven\ObjetiveBundle\Entity\Objetive 
     */
    public function getOperationalObjective() {
        return $this->operationalObjective;
    }

    function getCreatedBy() {
        return $this->createdBy;
    }

    function setCreatedBy(\Pequiven\SEIPBundle\Entity\User $createdBy) {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * Set ref
     *
     * @param string $ref
     * @return ArrangementProgram
     */
    public function setRef($ref) {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string 
     */
    public function getRef() {
        return $this->ref;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return ArrangementProgram
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set timeline
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\Timeline $timeline
     * @return ArrangementProgram
     */
    public function setTimeline(\Pequiven\ArrangementProgramBundle\Entity\Timeline $timeline = null) {
        $timeline->setArrangementProgram($this);
        $this->timeline = $timeline;

        return $this;
    }

    /**
     * Get timeline
     *
     * @return \Pequiven\ArrangementProgramBundle\Entity\Timeline 
     */
    public function getTimeline() {
        return $this->timeline;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ArrangementProgram
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ArrangementProgram
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Add arrangementProgramTrend
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionTrend $arrangementProgramTrend
     * @return Indicator
     */
    public function addArrangementProgramTrend(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionTrend $arrangementProgramTrend) {

        $this->arrangementProgramTrend->add($arrangementProgramTrend);

        return $this;
    }

    /**
     * Remove arrangementProgramTrend
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionTrend $arrangementProgramTrend
     */
    public function removeArrangementProgramTrend(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionTrend $arrangementProgramTrend) {
        $this->arrangementProgramTrend->removeElement($arrangementProgramTrend);
    }

    /**
     * Get arrangementProgramTrend
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArrangementProgramTrend() {
        return $this->arrangementProgramTrend;
    }

    /**
     * Add arrangementProgramCauses
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCauses $arrangementProgramCauses
     * @return Indicator
     */
    public function addArrangementProgramCauses(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCause $arrangementProgramCauses) {

        $this->arrangementProgramCauses->add($arrangementProgramCauses);

        return $this;
    }

    /**
     * Remove arrangementProgramCauses
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCause $arrangementProgramCauses
     */
    public function removeArrangementProgramCauses(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCause $arrangementProgramCauses) {
        $this->arrangementProgramCauses->removeElement($arrangementProgramCauses);
    }

    /**
     * Get arrangementProgramCauses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArrangementProgramCauses() {
        return $this->arrangementProgramCauses;
    }

    /**
     * Add arrangementProgramCausesAnalysis
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCauses $arrangementProgramCausesAnalysis
     * @return Indicator
     */
    public function addArrangementProgramCausesAnalysis(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCauseAnalysis $arrangementProgramCausesAnalysis) {

        $this->arrangementProgramCausesAnalysis->add($arrangementProgramCausesAnalysis);

        return $this;
    }

    /**
     * Remove arrangementProgramCausesAnalysis
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCauses $arrangementProgramCausesAnalysis
     */
    public function removeArrangementProgramCausesAnalysis(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionCauseAnalysis $arrangementProgramCausesAnalysis) {
        $this->arrangementProgramCauses->removeElement($arrangementProgramCausesAnalysis);
    }

    /**
     * Get arrangementProgramCauses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArrangementProgramCausesAnalysis() {
        return $this->arrangementProgramCausesAnalysis;
    }

    /**
     * Add arrangementProgramVerification
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionActionVerification $arrangementProgramVerification
     * @return Indicator
     */
    public function addArrangementProgramVerification(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionActionVerification $arrangementProgramVerification) {

        $this->arrangementProgramVerification->add($arrangementProgramVerification);

        return $this;
    }

    /**
     * Remove arrangementProgramVerification
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionActionVerification $arrangementProgramVerification
     */
    public function removeArrangementProgramVerification(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionActionVerification $arrangementProgramVerification) {
        $this->arrangementProgramCauses->removeElement($arrangementProgramVerification);
    }

    /**
     * Get arrangementProgramCauses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArrangementProgramVerification() {
        return $this->arrangementProgramVerification;
    }

    /**
     * Set details
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Details $details
     * @return ArrangementProgram
     */
    public function setDetails(\Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Details $details) {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Details 
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * Add histories
     *
     * @param \Pequiven\SEIPBundle\Entity\Historical $histories
     * @return ArrangementProgram
     */
    public function addHistory(\Pequiven\SEIPBundle\Entity\Historical $history) {
        $this->histories->add($history);

        return $this;
    }

    /**
     * Remove histories
     *
     * @param \Pequiven\SEIPBundle\Entity\Historical $histories
     */
    public function removeHistory(\Pequiven\SEIPBundle\Entity\Historical $histories) {
        $this->histories->removeElement($histories);
    }

    /**
     * Get histories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistories() {
        return $this->histories;
    }

    /**
     * Add observations
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Observation $observations
     * @return ArrangementProgram
     */
    public function addObservation(\Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Observation $observation) {
        $observation->setArrangementProgram($this);
        $this->observations->add($observation);

        return $this;
    }

    /**
     * Remove observations
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Observation $observations
     */
    public function removeObservation(\Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram\Observation $observations) {
        $this->observations->removeElement($observations);
    }

    /**
     * Get observations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObservations() {
        return $this->observations;
    }

    /**
     * Add responsibles
     *
     * @param \Pequiven\SEIPBundle\Entity\User $responsibles
     * @return ArrangementProgram
     */
    public function addResponsible(\Pequiven\SEIPBundle\Entity\User $responsibles) {
        $this->responsibles->add($responsibles);

        return $this;
    }

    /**
     * Remove responsibles
     *
     * @param \Pequiven\SEIPBundle\Entity\User $responsibles
     */
    public function removeResponsible(\Pequiven\SEIPBundle\Entity\User $responsibles) {
        $this->responsibles->removeElement($responsibles);
    }

    /**
     * Get responsibles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponsibles() {
        return $this->responsibles;
    }

    public function __toString() {
        return $this->getRef();
    }

    /**
     * Set totalAdvance
     *
     * @param float $totalAdvance
     * @return ArrangementProgram
     */
    public function setTotalAdvance($totalAdvance) {
        $this->totalAdvance = $totalAdvance;

        return $this;
    }

    /**
     * Get totalAdvance
     *
     * @return float 
     */
    public function getTotalAdvance() {
        return $this->totalAdvance;
    }

    /**
     * Set progressToDate
     *
     * @param float $progressToDate
     * @return ArrangementProgram
     */
    public function setProgressToDate($progressToDate) {
        $this->progressToDate = $progressToDate;

        return $this;
    }

    /**
     * Get progressToDate
     *
     * @return float 
     */
    public function getProgressToDate() {
        return $this->progressToDate;
    }

    /**
     * Devuelve el valor que sera tomado en cuenta para los resuldatos
     * @return type
     */
    public function getResult() {
        return $this->progressToDate;
    }

    public function setResult($result) {
        $this->progressToDate = $result;
    }

    /**
     * Set resultReal
     * indicators
     * @param float $resultReal
     * @return Indicator
     */
    public function setResultReal($resultReal) {
        $this->resultReal = $resultReal;

        return $this;
    }

    function getRealResult() {
        return $this->realResult;
    }

    function setRealResult($realResult) {
        $this->realResult = $realResult;
    }

    public function getWeight() {
        return null;
    }

    public function getResultWithWeight() {
        $result = ( $this->getResult() * $this->getWeight()) / 100;
        return $result;
    }

    public function updateLastDateCalculateResult() {
        $this->lastDateCalculateResult = new \DateTime();
    }

    public function clearLastDateCalculateResult() {
        $this->lastDateCalculateResult = null;
    }

    public function isAvailableInResult() {
        $valid = $this->isAvailableInResult;
        if ($this->getStatus() == self::STATUS_REJECTED) {
            $valid = false;
        }
        return $valid;
    }

    function getDescription() {
        return $this->description;
    }

    function getIsAvailableInResult() {
        return $this->isAvailableInResult;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setIsAvailableInResult($isAvailableInResult) {
        $this->isAvailableInResult = $isAvailableInResult;
    }

    function getDeletedAt() {
        return $this->deletedAt;
    }

    function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    function isCouldBePenalized() {
        return $this->couldBePenalized;
    }

    function isForcePenalize() {
        return $this->forcePenalize;
    }

    function setCouldBePenalized($couldBePenalized) {
        $this->couldBePenalized = $couldBePenalized;

        return $this;
    }

    function setForcePenalize($forcePenalize) {
        $this->forcePenalize = $forcePenalize;

        return $this;
    }

    function getShowEvolutionView() {
        return $this->showEvolutionView;
    }

    function isShowEvolutionView() {
        return $this->showEvolutionView;
    }

    function setShowEvolutionView($showEvolutionView) {
        $this->showEvolutionView = $showEvolutionView;
    }

    /**
     * Set updateResultByAdmin
     *
     * @param boolean $updateResultByAdmin
     * @return ArrangementProgram
     */
    public function setUpdateResultByAdmin($updateResultByAdmin) {
        $this->updateResultByAdmin = $updateResultByAdmin;

        return $this;
    }

    /**
     * Get updateResultByAdmin
     *
     * @return boolean 
     */
    public function getUpdateResultByAdmin() {
        return $this->updateResultByAdmin;
    }

    /**
     * Set resultModified
     * @param float $resultModified
     * @return ArrangementProgram
     */
    public function setResultModified($resultModified) {
        $this->resultModified = $resultModified;

        return $this;
    }

    /**
     * Get resultModified
     *
     * @return float 
     */
    public function getResultModified() {
        return $this->resultModified;
    }

    /**
     * Set penalty
     * @param integer $penalty
     * @return Goal
     */
    public function setPenalty($penalty) {
        $this->penalty = $penalty;

        return $this;
    }

    /**
     * Get penalty
     *
     * @return integer 
     */
    public function getPenalty() {
        return $this->penalty;
    }

    /**
     * Set resultbeforepenalty
     * @param float $resultBeforepenalty
     * @return Goal
     */
    public function setresultBeforepenalty($resultBeforepenalty) {
        $this->resultBeforepenalty = $resultBeforepenalty;

        return $this;
    }

    /**
     * Get resultBeforepenalty
     *
     * @return float 
     */
    public function getresultBeforepenalty() {
        return $this->resultBeforepenalty;
    }

    /**
     * 
     * @return type
     */
    function getResultReal() {
        return $this->resultReal;
    }

    public function __clone() {
        if ($this->id) {
            $this->id = null;
            $this->ref = null;
            $this->period = null;
            $this->description = null;
            $this->status = self::STATUS_DRAFT;

            $this->timeline = new Timeline();
            $this->createdBy = null;
            $this->createdAt = null;
            $this->updatedAt = null;

            $this->details = new ArrangementProgram\Details();
            $this->histories = new \Doctrine\Common\Collections\ArrayCollection();
            $this->observations = new \Doctrine\Common\Collections\ArrayCollection();
            $this->totalAdvance = 0;
            $this->progressToDate = 0;
            $this->lastDateCalculateResult = null;
        }
    }

}
