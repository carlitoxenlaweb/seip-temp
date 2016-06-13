<?php

namespace Pequiven\SEIPBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Pequiven\MasterBundle\Entity\Rol;
use Tecnocreaciones\Vzla\GovernmentBundle\Model\UserInterface;
use Tecnocreaciones\Bundle\BoxBundle\Model\UserBoxInterface;
use Pequiven\SEIPBundle\Model\Common\CommonObject;

/**
 * User model
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 * 
 * @ORM\Entity(repositoryClass="Pequiven\SEIPBundle\Repository\UserRepository")
 * @ORM\Table(name="seip_user")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=false)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=false)),
 * })
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements UserInterface, UserBoxInterface, PeriodItemInterface {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var type 
     */
    protected $id;

    /**
     * @ORM\Column(name="num_personal",type="integer",nullable=true)
     */
    private $numPersonal;

    /**
     * @var string
     */
    protected $username;

    /**
     * @ORM\OneToMany(targetEntity="\Pequiven\SEIPBundle\Entity\User", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="\Pequiven\SEIPBundle\Entity\User", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * Dirección
     * @var \Pequiven\MasterBundle\Entity\Direction
     * @ORM\ManyToOne(targetEntity="\Pequiven\MasterBundle\Entity\Direction")
     * @ORM\JoinColumn(name="fk_direction", referencedColumnName="id")
     */
    private $direction;

    /**
     * Complejo
     * 
     * @var \Pequiven\MasterBundle\Entity\Complejo
     * @ORM\ManyToOne(targetEntity="\Pequiven\MasterBundle\Entity\Complejo")
     * @ORM\JoinColumn(name="fk_complejo", referencedColumnName="id")
     */
    private $complejo;

    /**
     * Gerencia
     * 
     * @var \Pequiven\MasterBundle\Entity\Gerencia
     * @ORM\ManyToOne(targetEntity="\Pequiven\MasterBundle\Entity\Gerencia")
     * @ORM\JoinColumn(name="fk_gerencia", referencedColumnName="id")
     */
    private $gerencia;

    /**
     * GerenciaSecond
     * 
     * @var \Pequiven\MasterBundle\Entity\GerenciaSecond
     * @ORM\ManyToOne(targetEntity="\Pequiven\MasterBundle\Entity\GerenciaSecond")
     * @ORM\JoinColumn(name="fk_gerencia_second", referencedColumnName="id")
     */
    private $gerenciaSecond;

    /**
     * Cargo
     * 
     * @var \Pequiven\MasterBundle\Entity\Cargo
     * @ORM\ManyToOne(targetEntity="\Pequiven\MasterBundle\Entity\Cargo")
     * @ORM\JoinColumn(name="fk_cargo", referencedColumnName="id")
     */
    private $cargo;

    /**
     * @ORM\OneToMany(targetEntity="\Pequiven\MasterBundle\Entity\RolUser", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $groups;

    /**
     * Programas de gestion
     * 
     * @var \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram
     * @ORM\ManyToMany(targetEntity="Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram",mappedBy="responsibles")
     */
    protected $arrangementPrograms;

    /**
     * Metas
     * 
     * @var \Pequiven\ArrangementProgramBundle\Entity\Goal
     * @ORM\ManyToMany(targetEntity="Pequiven\ArrangementProgramBundle\Entity\Goal",mappedBy="responsibles")
     */
    private $goals;

    /**
     * Supervisores
     * @var User
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\User",mappedBy="supervised",cascade={"persist"})
     */
    private $supervisors;

    /**
     * Supervisados
     * @var User
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\User",inversedBy="supervisors")
     * @ORM\JoinTable(name="user_supervised",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="supervised_user_id", referencedColumnName="id")}
     *      )
     */
    private $supervised;

    /**
     * Configuracion del usuario
     * 
     * @var \Pequiven\SEIPBundle\Entity\User\Configuration
     * @ORM\OneToOne(targetEntity="Pequiven\SEIPBundle\Entity\User\Configuration",inversedBy="user",cascade={"persist","remove"})
     */
    private $configuration;

    /**
     * Boxes o widgets del usuario
     * 
     * @var \Pequiven\SEIPBundle\Entity\Box\ModelBox
     * @ORM\OneToMany(targetEntity="Pequiven\SEIPBundle\Entity\Box\ModelBox",mappedBy="user")
     */
    private $boxes;

    /**
     * Estatus del trabajador
     * @var integer
     *
     * @ORM\Column(name="status_worker", type="integer")
     */
    protected $statusWorker = 1;

    /**
     * Trabajador de Empresa Filiar o Mixta
     * @var boolean
     *
     * @ORM\Column(name="affiliated_worker", type="boolean", nullable=true)
     */
    protected $affiliatedWorker = false;

    /**
     * Periodo.
     * 
     * @var \Pequiven\SEIPBundle\Entity\Period
     * @ORM\ManyToOne(targetEntity="Pequiven\SEIPBundle\Entity\Period")
     */
    private $period;

    /**
     * Reportes de plantas que puede cargar el usuario
     * @var DataLoad\PlantReport
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\DataLoad\PlantReport",inversedBy="users")
     */
    private $plantReports;

    /**
     * Reportes de plantas que puede cargar el usuario
     * @var DataLoad\ReportTemplate
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\DataLoad\ReportTemplate",inversedBy="users")
     */
    private $reportTemplates;

    /**
     * Reportes de plantas de despacho que puede carga el usuario
     * @var Delivery\ReportTemplateDelivery
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\Delivery\ReportTemplateDelivery",inversedBy="users")
     */
    private $reportTemplatesDelivery;

    /**
     * @ORM\ManyToOne(targetEntity="\Pequiven\SEIPBundle\Entity\Politic\WorkStudyCircle", inversedBy="userWorkerId")
     * @ORM\JoinColumn(name="workStudyCircle_id", referencedColumnName="id")
     * */
    private $workStudyCircle;

    /**
     * cedula
     * @var string
     * @ORM\Column(name="identification",type="integer",length=9,nullable=true)
     */
    private $identification;

    /**
     * celular
     * @var string
     * @ORM\Column(name="cellphone",type="string",length=100,nullable=true)
     */
    private $cellphone;

    /**
     * extension
     * @var string
     * @ORM\Column(name="ext",type="string",length=6,nullable=true)
     */
    private $ext;

    /**
     * @ORM\ManyToMany(targetEntity="\Pequiven\SEIPBundle\Entity\Politic\WorkStudyCircle", mappedBy="members")
     */
    private $workStudyCircles;

    /**
     * aciones
     * 
     * @var \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionAction
     * @ORM\ManyToMany(targetEntity="\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionAction", mappedBy="responsible") 
     * 
     */
    private $evolutionAction;

    /**
     * @var Pequiven\ArrangementProgramBundle\Entity\MovementEmployee\MovementEmployee
     * @ORM\OneToMany(targetEntity="Pequiven\ArrangementProgramBundle\Entity\MovementEmployee\MovementEmployee", mappedBy="User")
     * */
    private $movementEmployee;

    /**
     * @var Pequiven\SEIPBundle\Entity\User\FeeStructure
     * @ORM\OneToMany(targetEntity="\Pequiven\SEIPBundle\Entity\User\FeeStructure", mappedBy="User")
     * */
    private $feeStructure;

    /**
     * @var Pequiven\SEIPBundle\Entity\User\MovementFeeStructure
     * @ORM\OneToMany(targetEntity="\Pequiven\SEIPBundle\Entity\User\MovementFeeStructure",mappedBy="User",cascade={"persist"}))
     */
    protected $movementFeeStructure;

    /**
     * Trimestre que esta permitido notificar en el Módulo de Operaciones/Producción
     * @var integer
     *
     * @ORM\Column(name="quarterToLoadOperationProduction", type="integer")
     */
    protected $quarterToLoadOperationProduction = 0;

    /**
     * @ORM\Column(name="notify",type="integer",nullable=true)
     */
    private $notify;

    /**
     *
     * @var \Pequiven\SEIPBundle\Entity\User\Notification
     * @ORM\OneToMany(targetEntity="\Pequiven\SEIPBundle\Entity\User\Notification", mappedBy="user") 
     */
    private $notification;

    /**
     * aciones
     * 
     * @var \Pequiven\SIGBundle\Entity\Tracing\Standardization
     * @ORM\ManyToMany(targetEntity="\Pequiven\SIGBundle\Entity\Tracing\Standardization", mappedBy="responsible") 
     * 
     */
    private $maintenanceResponsibles;

    /**
     * Default Connection
     * @var integer
     * @ORM\ManyToOne(targetEntity="\Pequiven\MasterBundle\Entity\MasterConnection")
     * @ORM\JoinColumn(name="default_conn", referencedColumnName="id")
     */
    protected $defaultConnection;

    /**
     * User Company
     * @var integer
     * @ORM\ManyToOne(targetEntity="\Pequiven\SEIPBundle\Entity\CEI\Company", cascade={"persist"})
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    protected $company;

    /**
     * @ORM\ManyToMany(targetEntity="\Pequiven\MasterBundle\Entity\MasterConnection", inversedBy="user", cascade={"persist","remove"})
     * @ORM\JoinTable(name="seip_user_connection",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="connection_id", referencedColumnName="id")}
     * )
     */
    protected $connections;

    /**
     * @var \Pequiven\SEIPBundle\Entity\CEI\Company
     * @ORM\ManyToMany(targetEntity="Pequiven\SEIPBundle\Entity\CEI\Company", inversedBy="users", cascade={"persist","remove"})
     * @ORM\JoinTable(name="seip_user_company",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
     * )
     */
    protected $userCompanies;

    //Just for roles!
    private $currentCompany;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->goals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangementPrograms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supervisors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supervised = new \Doctrine\Common\Collections\ArrayCollection();
        $this->boxes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->plantReports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reportTemplates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reportTemplatesDelivery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workStudyCircles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->evolutionAction = new \Doctrine\Common\Collections\ArrayCollection();
        $this->feeStructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notification = new \Doctrine\Common\Collections\ArrayCollection();
        $this->maintenanceResponsibles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->connections = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userCompanies = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
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
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return User
     */
    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt() {
        return $this->deletedAt;
    }

    /**
     * Set numPersonal
     *
     * @param integer $numPersonal
     * @return User
     */
    public function setNumPersonal($numPersonal) {
        $this->numPersonal = $numPersonal;

        return $this;
    }

    /**
     * Get numPersonal
     *
     * @return integer
     */
    public function getNumPersonal() {
        return $this->numPersonal;
    }

    /**
     * Add children
     *
     * @param \Pequiven\SEIPBundle\Entity\User $children
     * @return User
     */
    public function addChild(\Pequiven\SEIPBundle\Entity\User $children) {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Pequiven\SEIPBundle\Entity\User $children
     */
    public function removeChild(\Pequiven\SEIPBundle\Entity\User $children) {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Pequiven\SEIPBundle\Entity\User $parent
     * @return User
     */
    public function setParent(\Pequiven\SEIPBundle\Entity\User $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Pequiven\SEIPBundle\Entity\User 
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set Complejo
     *
     * @param \Pequiven\MasterBundle\Entity\Complejo $complejo
     * @return User
     */
    public function setComplejo(\Pequiven\MasterBundle\Entity\Complejo $complejo = null) {
        $this->complejo = $complejo;

        return $this;
    }

    /**
     * Get Complejo
     *
     * @return \Pequiven\MasterBundle\Entity\Complejo 
     */
    public function getComplejo() {
        return $this->complejo;
    }

    /**
     * Set Gerencia
     *
     * @param \Pequiven\MasterBundle\Entity\Gerencia $gerencia
     * @return User
     */
    public function setGerencia(\Pequiven\MasterBundle\Entity\Gerencia $gerencia = null) {
        $this->gerencia = $gerencia;

        return $this;
    }

    /**
     * Get Gerencia
     *
     * @return \Pequiven\MasterBundle\Entity\Gerencia 
     */
    public function getGerencia() {
        return $this->gerencia;
    }

    /**
     * Set Cargo
     *
     * @param \Pequiven\MasterBundle\Entity\Cargo $cargo
     * @return User
     */
    public function setCargo(\Pequiven\MasterBundle\Entity\Cargo $cargo = null) {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get Cargo
     *
     * @return \Pequiven\MasterBundle\Entity\Cargo 
     */
    public function getCargo() {
        return $this->cargo;
    }

    /**
     * Set gerenciaSecond
     *
     * @param \Pequiven\MasterBundle\Entity\GerenciaSecond $gerenciaSecond
     * @return User
     */
    public function setGerenciaSecond(\Pequiven\MasterBundle\Entity\GerenciaSecond $gerenciaSecond = null) {
        $this->gerenciaSecond = $gerenciaSecond;

        return $this;
    }

    /**
     * Get gerenciaSecond
     *
     * @return \Pequiven\MasterBundle\Entity\GerenciaSecond 
     */
    public function getGerenciaSecond() {
        return $this->gerenciaSecond;
    }

    /**
     * Set direction
     *
     * @param \Pequiven\MasterBundle\Entity\Direction $direction
     * @return Gerencia
     */
    public function setDirection(\Pequiven\MasterBundle\Entity\Direction $direction = null) {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return \Pequiven\MasterBundle\Entity\Direction 
     */
    public function getDirection() {
        return $this->direction;
    }

    public function __toString() {
        return sprintf("%s %s (%s)", $this->getFirstName(), $this->getLastName(), $this->getNumPersonal());
    }

    /**
     * Add goals
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\Goal $goals
     * @return User
     */
    public function addGoal(\Pequiven\ArrangementProgramBundle\Entity\Goal $goals) {
        $this->goals[] = $goals;

        return $this;
    }

    /**
     * Remove goals
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\Goal $goals
     */
    public function removeGoal(\Pequiven\ArrangementProgramBundle\Entity\Goal $goals) {
        $this->goals->removeElement($goals);
    }

    /**
     * Get goals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoals() {
        return $this->goals;
    }

    /**
     * 
     */
    public function isAllowSuperAdmin() {
        $isSuperAdmin = false;
        $groupsLevelAdmin = array(
            Rol::ROLE_ADMIN,
            Rol::ROLE_SUPER_ADMIN
        );
//        $level = Rol::ROLE_SUPER_ADMIN;
        $groups = $this->getGroups();
        foreach ($groups as $group) {
            if (in_array($group->getRol()->getLevel(), $groupsLevelAdmin)) {
                $isSuperAdmin = true;
            }
        }
        return $isSuperAdmin;
    }

    /**
     * Devuelve el nivel del rol asignado
     * @return integer
     */
    public function getLevelByGroup($type = CommonObject::TYPE_LEVEL_USER_ONLY_OWNER) {
        if (!isset($this->levelByGroup)) {
            $level = 0;
            $groups = $this->getGroups();
            $groupsLevelAdmin = array(
                Rol::ROLE_ADMIN,
                Rol::ROLE_SUPER_ADMIN
            );
            foreach ($groups as $group) {
                $group = $group->getRol();
                if ($type == CommonObject::TYPE_LEVEL_USER_ONLY_OWNER) {
                    if ($group->getLevel() > $level && !in_array($group->getLevel(), $groupsLevelAdmin) && $group->getTypeRol() == Rol::TYPE_ROL_OWNER) {
                        $level = $group->getLevel();
                    }
                } elseif ($type == CommonObject::TYPE_LEVEL_USER_ALL) {
                    if ($group->getLevel() > $level && !in_array($group->getLevel(), $groupsLevelAdmin)) {
                        $level = $group->getLevel();
                    }
                }
            }
            $this->levelByGroup = $level;
        }
        return $this->levelByGroup;
    }

    public function getRealGroup() {
        if (!isset($this->realGroup)) {
            $level = 0;
            $groups = $this->getGroups();
            $groupsLevelAdmin = array(
                Rol::ROLE_ADMIN,
                Rol::ROLE_SUPER_ADMIN
            );
            $realGroup = null;
            foreach ($groups as $group) {
                $group = $group->getRol();
                if ($group->getLevel() > $level && !in_array($group->getLevel(), $groupsLevelAdmin)) {
                    $level = $group->getLevel();
                    $realGroup = $group;
                }
            }
            $this->levelByGroup = $level;
            $this->realGroup = $realGroup;
        }
        return $this->realGroup;
    }

    /**
     * Devuelve el nivel real del rol asignado, nunca devuelve rol auxiliar
     * 
     * @return integer
     */
    public function getLevelRealByGroup() {
        if (!isset($this->levelRealByGroup)) {
            $this->levelRealByGroup = Rol::getRoleLevel($this->getLevelByGroup());
        }
        return $this->levelRealByGroup;
    }

    /**
     * Devuelve el nivel real del rol asignado, nunca devuelve rol auxiliar
     * 
     * @return integer
     */
    public function getLevelAllByGroup() {
        if (!isset($this->levelRealByGroup)) {
            $this->levelRealByGroup = Rol::getRoleLevel($this->getLevelByGroup(CommonObject::TYPE_LEVEL_USER_ALL));
        }
        return $this->levelRealByGroup;
    }

    public function getFullNameUser() {
        return $this->firstname . ' ' . $this->lastname . ' (' . $this->numPersonal . ' | ' . $this->username . ')';
    }

    public function getOnlyFullNameUser() {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Add supervisors
     *
     * @param \Pequiven\SEIPBundle\Entity\User $supervisors
     * @return User
     */
    public function addSupervisor(\Pequiven\SEIPBundle\Entity\User $supervisors) {
        $this->supervisors->add($supervisors);

        return $this;
    }

    /**
     * Remove supervisors
     *
     * @param \Pequiven\SEIPBundle\Entity\User $supervisors
     */
    public function removeSupervisor(\Pequiven\SEIPBundle\Entity\User $supervisors) {
        $this->supervisors->removeElement($supervisors);
    }

    /**
     * Get supervisors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupervisors() {
        return $this->supervisors;
    }

    /**
     * Add supervised
     *
     * @param \Pequiven\SEIPBundle\Entity\User $supervised
     * @return User
     */
    public function addSupervised(\Pequiven\SEIPBundle\Entity\User $supervised) {
        $this->supervised->add($supervised);

        return $this;
    }

    /**
     * Remove supervised
     *
     * @param \Pequiven\SEIPBundle\Entity\User $supervised
     */
    public function removeSupervised(\Pequiven\SEIPBundle\Entity\User $supervised) {
        $this->supervised->removeElement($supervised);
    }

    /**
     * Get supervised
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupervised() {
        return $this->supervised;
    }

    /**
     * Add arrangementPrograms
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram $arrangementPrograms
     * @return User
     */
    public function addArrangementProgram(\Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram $arrangementPrograms) {
        $this->arrangementPrograms->add($arrangementPrograms);

        return $this;
    }

    /**
     * Remove arrangementPrograms
     *
     * @param \Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram $arrangementPrograms
     */
    public function removeArrangementProgram(\Pequiven\ArrangementProgramBundle\Entity\ArrangementProgram $arrangementPrograms) {
        $this->arrangementPrograms->removeElement($arrangementPrograms);
    }

    /**
     * Get arrangementPrograms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArrangementPrograms() {
        return $this->arrangementPrograms;
    }

    /**
     * Set configuration
     *
     * @param \Pequiven\SEIPBundle\Entity\User\Configuration $configuration
     * @return User
     */
    public function setConfiguration(\Pequiven\SEIPBundle\Entity\User\Configuration $configuration = null) {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return \Pequiven\SEIPBundle\Entity\User\Configuration 
     */
    public function getConfiguration() {
        return $this->configuration;
    }

    /**
     * Add boxes
     *
     * @param \Tecnocreaciones\Bundle\BoxBundle\Model\ModelBoxInterface $boxes
     * @return User
     */
    public function addModelBox(\Tecnocreaciones\Bundle\BoxBundle\Model\ModelBoxInterface $boxes) {
        $this->boxes[] = $boxes;

        return $this;
    }

    /**
     * Remove boxes
     *
     * @param \Tecnocreaciones\Bundle\BoxBundle\Model\ModelBoxInterface $boxes
     */
    public function removeModelBox(\Tecnocreaciones\Bundle\BoxBundle\Model\ModelBoxInterface $boxes) {
        $this->boxes->removeElement($boxes);
    }

    /**
     * Get boxes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModelBoxes() {
        return $this->boxes;
    }

    /**
     * Set status worker
     *
     * @param integer $statusWorker
     * @return ArrangementProgram
     */
    public function setStatusWorker($statusWorker) {
        $this->statusWorker = $statusWorker;

        return $this;
    }

    /**
     * Get status worker
     *
     * @return integer 
     */
    public function getStatusWorker() {
        return $this->statusWorker;
    }

    function getPeriod() {
        return $this->period;
    }

    function setPeriod(\Pequiven\SEIPBundle\Entity\Period $period) {
        $this->period = $period;

        return $this;
    }

    /**
     * Add plantReports
     *
     * @param \Pequiven\SEIPBundle\Entity\DataLoad\PlantReport $plantReports
     * @return User
     */
    public function addPlantReport(\Pequiven\SEIPBundle\Entity\DataLoad\PlantReport $plantReports) {
        $this->plantReports[] = $plantReports;

        return $this;
    }

    /**
     * Remove plantReports
     *
     * @param \Pequiven\SEIPBundle\Entity\DataLoad\PlantReport $plantReports
     */
    public function removePlantReport(\Pequiven\SEIPBundle\Entity\DataLoad\PlantReport $plantReports) {
        $this->plantReports->removeElement($plantReports);
    }

    /**
     * Get plantReports
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlantReports() {
        return $this->plantReports;
    }

    /**
     * Add reportTemplates
     *
     * @param \Pequiven\SEIPBundle\Entity\DataLoad\PlantReport $reportTemplates
     * @return User
     */
    public function addReportTemplates(\Pequiven\SEIPBundle\Entity\DataLoad\ReportTemplate $reportTemplates) {
        $this->reportTemplates[] = $reportTemplates;

        return $this;
    }

    /**
     * Remove reportTemplates
     *
     * @param \Pequiven\SEIPBundle\Entity\DataLoad\ReportTemplate $reportTemplates
     */
    public function removeReportTemplates(\Pequiven\SEIPBundle\Entity\DataLoad\ReportTemplate $reportTemplates) {
        $this->reportTemplates->removeElement($reportTemplates);
    }

    /**
     * Get reportTemplates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReportTemplates() {
        return $this->reportTemplates;
    }

    /**
     * 
     * @param \Pequiven\SEIPBundle\Entity\Delivery\ReportTemplateDelivery $reportTemplatesDelivery
     * @return \Pequiven\SEIPBundle\Entity\User
     */
    public function addReportTemplatesDelivery(\Pequiven\SEIPBundle\Entity\Delivery\ReportTemplateDelivery $reportTemplatesDelivery) {
        $this->reportTemplatesDelivery[] = $reportTemplatesDelivery;

        return $this;
    }

    /**
     * 
     * @param \Pequiven\SEIPBundle\Entity\Delivery\ReportTemplateDelivery $reportTemplatesDelivery
     */
    public function removeReportTemplatesDelivery(\Pequiven\SEIPBundle\Entity\Delivery\ReportTemplateDelivery $reportTemplatesDelivery) {
        $this->reportTemplatesDelivery->removeElement($reportTemplatesDelivery);
    }

    /**
     * 
     * @return type
     */
    public function getReportTemplatesDelivery() {
        return $this->reportTemplatesDelivery;
    }

    /**
     * 
     * @return type
     */
    public function setWorkStudyCircle(Politic\WorkStudyCircle $workStudyCircle = null) {
        $this->workStudyCircle = $workStudyCircle;

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getWorkStudyCircle() {
        return $this->workStudyCircle;
    }

    public function setIndentification($identification) {
        $this->identification = $identification;
    }

    public function getIndentification() {
        return $this->identification;
    }

    public function setCellphone($cellphone) {
        $this->cellphone = $cellphone;
    }

    public function getCellphone() {
        return $this->cellphone;
    }

    public function setExt($ext) {
        $this->ext = $ext;
    }

    public function getExt() {
        return $this->ext;
    }

    /**
     * Add workStudyCircles
     *
     * @param \Pequiven\SEIPBundle\Entity\Politic\WorkStudyCircle $workStudyCircle
     * @return User
     */
    public function addWorkStudyCircles(\Pequiven\SEIPBundle\Entity\Politic\WorkStudyCircle $workStudyCircle) {
        $this->workStudyCircles[] = $workStudyCircle;

        return $this;
    }

    /**
     * Remove workStudyCircles
     *
     * @param \Pequiven\SEIPBundle\Entity\Politic\WorkStudyCircle $workStudyCircle
     */
    public function removeWorkStudyCircles(\Pequiven\SEIPBundle\Entity\Politic\WorkStudyCircle $workStudyCircle) {
        $this->workStudyCircles->removeElement($workStudyCircle);
    }

    /**
     * Get workStudyCircles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkStudyCircles() {
        return $this->workStudyCircles;
    }

    /**
     * Add evolutionAction
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionAction $evolutionAction
     * @return User
     */
    public function addEvolutionAction(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionAction $evolutionAction) {
        $this->evolutionAction[] = $evolutionAction;

        return $this;
    }

    /**
     * Remove evolutionAction
     *
     * @param \Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionAction $evolutionAction
     */
    public function removeEvolutionAction(\Pequiven\IndicatorBundle\Entity\Indicator\EvolutionIndicator\EvolutionAction $evolutionAction) {
        $this->evolutionAction->removeElement($evolutionAction);
    }

    /**
     * Get evolutionAction
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvolutionAction() {
        return $this->evolutionAction;
    }

    /**
     * 
     * @param type $quarterToLoadOperationProduction
     * @return 
     */
    public function setQuarterToLoadOperationProduction($quarterToLoadOperationProduction) {
        $this->quarterToLoadOperationProduction = $quarterToLoadOperationProduction;
    }

    /**
     * Get quarterToLoadOperationProduction
     * @return integer
     */
    public function getQuarterToLoadOperationProduction() {
        return $this->quarterToLoadOperationProduction;
    }

    /**
     * 
     * @param type $notify
     * @return 
     */
    public function setNotify($notify) {
        $this->notify = $notify;
    }

    /**
     * Get notify
     * @return integer
     */
    public function getNotify() {
        return $this->notify;
    }

    /**
     * Add maintenanceResponsibles
     *
     * @param \Pequiven\SIGBundle\Entity\Tracing\Standardization $maintenanceResponsibles
     * @return User
     */
    public function addMaintenanceResponsibles(\Pequiven\SIGBundle\Entity\Tracing\Standardization $maintenanceResponsibles) {
        $this->maintenanceResponsibles[] = $maintenanceResponsibles;

        return $this;
    }

    /**
     * Remove maintenanceResponsibles
     *
     * @param \Pequiven\SIGBundle\Entity\Tracing\Standardization $maintenanceResponsibles
     */
    public function removeMaintenanceResponsibles(\Pequiven\SIGBundle\Entity\Tracing\Standardization $maintenanceResponsibles) {
        $this->maintenanceResponsibles->removeElement($maintenanceResponsibles);
    }

    /**
     * Get maintenanceResponsibles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMaintenanceResponsibles() {
        return $this->maintenanceResponsibles;
    }

    
    
    /**
     * Get defaultConnection
     *
     * @return \Pequiven\MasterBundle\Entity\MasterConnection
     */
    public function getDefaultConnection(){
        return $this->defaultConnection;
    }

    /**
     * Set defaultConnection
     *
     * @param integer $defaultConnection
     *
     * @return $defaultConnection
     */
    public function setDefaultConnection(\Pequiven\MasterBundle\Entity\MasterConnection $conn){
        $this->defaultConnection = $conn;
        return $this;
    }

    /**
     * Get company
     *
     * @return \Pequiven\SEIPBundle\Entity\CEI\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set company
     *
     * @param \Pequiven\SEIPBundle\Entity\CEI\Company $company
     * @return Location
     */
    public function setCompany(\Pequiven\SEIPBundle\Entity\CEI\Company $company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Add connection
     *
     * @param \Pequiven\MasterBundle\Entity\MasterConnection $connection
     * @return User
     */
    public function addConnection(\Pequiven\MasterBundle\Entity\MasterConnection $connection) {
        $this->connections[] = $connection;

        return $this;
    }

    /**
     * Remove connection
     *
     * @param \Pequiven\MasterBundle\Entity\MasterConnection $connection
     */
    public function removeConnection(\Pequiven\MasterBundle\Entity\MasterConnection $connection) {
        $this->connections->removeElement($connection);
    }

    /**
     * Get connections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConnections() {
        return $this->connections;
    }

    /**
     * Set connections
     *
     * @return User
     */
    public function setConnections($connections) {
        $this->connections = $connections;

        return $this;
    }

    /**
     * Add userCompany
     *
     * @param \Pequiven\SEIPBundle\Entity\CEI\Company $userCompanies
     * @return User
     */
    public function addUserCompany(\Pequiven\SEIPBundle\Entity\CEI\Company $company) {
        $this->userCompanies->add($company);

        return $this;
    }

    /**
     * Remove userCompany
     *
     * @param \Pequiven\SEIPBundle\Entity\CEI\Company $userCompanies
     */
    public function removeUserCompany(\Pequiven\SEIPBundle\Entity\CEI\Company $company) {
        $this->userCompanies->removeElement($company);
    }

    /**
     * Get userCompanies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserCompanies() {
        return $this->userCompanies;
    }

    /**
     * Set userCompanies
     *
     * @return User
     */
    public function setUserCompanies($companies) {
        $this->userCompanies = $companies;

        return $this;
    }

    public function getCurrentCompany()
    {
        return $this->currentCompany;
    }

    public function getCurrentCompanyId()
    {
        return is_null($this->getCurrentCompany()) ?
            $this->getCompany()->getId() : $this->getCurrentCompany()->getId();
    }

    public function setCurrentCompany(\Pequiven\SEIPBundle\Entity\CEI\Company $company)
    {
        $this->currentCompany = $company;
        return $this;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;        
        if(is_null($this->getCompany()))
            return $roles;

        foreach ($this->getGroups() as $group) {
            if($this->getCurrentCompanyId() === $group->getCompany()->getId())
                $roles = array_merge($roles, $group->getRol()->getRoles());
        }
        
        $roles[] = static::ROLE_DEFAULT;
        return array_unique($roles);
    }

    /**
     * Add roleUser
     *
     * @param \Pequiven\MasterBundle\Entity\RolUser $roleUser
     *
     * @return Rol
     */
    public function addGroups(\Pequiven\MasterBundle\Entity\RolUser $roleUser)
    {
        $this->groups[] = $roleUser;

        return $this;
    }

    /**
     * Remove roleUser
     *
     * @param \Pequiven\MasterBundle\Entity\RolUser $roleUser
     */
    public function removeGroups(\Pequiven\MasterBundle\Entity\RolUser $roleUser)
    {
        $this->groups->removeElement($roleUser);
    }

    /**
     * Get roleUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    function getFeeStructure() {
        return $this->feeStructure;
    }

    function setFeeStructure(\Pequiven\SEIPBundle\Entity\User\FeeStructure $feeStructure) {
        $this->feeStructure = $feeStructure;
    }

    function getUsername() {
        return $this->username;
    }

    function getBoxes() {
        return $this->boxes;
    }

    function getAffiliatedWorker() {
        return $this->affiliatedWorker;
    }

    function getIdentification() {
        return $this->identification;
    }

    function getMovementEmployee() {
        return $this->movementEmployee;
    }

    function getMovementFeeStructure() {
        return $this->movementFeeStructure;
    }

    function getNotification() {
        return $this->notification;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setBoxes(\Pequiven\SEIPBundle\Entity\Box\ModelBox $boxes) {
        $this->boxes = $boxes;
    }

    function setAffiliatedWorker($affiliatedWorker) {
        $this->affiliatedWorker = $affiliatedWorker;
    }

    function setIdentification($identification) {
        $this->identification = $identification;
    }

    function setMovementEmployee(\Pequiven\ArrangementProgramBundle\Entity\MovementEmployee\MovementEmployee $movementEmployee) {
        $this->movementEmployee = $movementEmployee;
    }

    function setMovementFeeStructure(\Pequiven\SEIPBundle\Entity\User\MovementFeeStructure $movementFeeStructure) {
        $this->movementFeeStructure = $movementFeeStructure;
    }

    function setNotification(\Pequiven\SEIPBundle\Entity\User\Notification $notification) {
        $this->notification = $notification;
    }

}
