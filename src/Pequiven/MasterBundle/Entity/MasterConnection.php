<?php

namespace Pequiven\MasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Table(name="seip_connection")
 * @ORM\Entity(repositoryClass="Pequiven\MasterBundle\Repository\MasterConnectionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MasterConnection
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="\Pequiven\SEIPBundle\Entity\CEI\Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    protected $company;

    /**
     * @ORM\ManyToMany(targetEntity="\Pequiven\SEIPBundle\Entity\User", mappedBy="connections", cascade={"remove"})
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct() {
        $this->userConnection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return MasterConnection
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add userConnection
     *
     * @param \Pequiven\SEIPBundle\Entity\User $userConnection
     *
     * @return MasterConnection
     */
    public function addUserConnection(\Pequiven\SEIPBundle\Entity\User $userConnection)
    {
        $this->userConnection[] = $userConnection;
        return $this;
    }

    /**
     * Remove userConnection
     *
     * @param \Pequiven\SEIPBundle\Entity\User $userConnection
     */
    public function removeUserConnection(\Pequiven\SEIPBundle\Entity\User $userConnection)
    {
        $this->userConnection->removeElement($userConnection);
    }

    /**
     * Get userConnection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserConnection()
    {
        return $this->userConnection;
    }

    /**
     * Set company
     *
     * @param \Pequiven\SEIPBundle\Entity\CEI\Company $company
     *
     * @return MasterConnection
     */
    public function setCompany(\Pequiven\SEIPBundle\Entity\CEI\Company $company = null)
    {
        $this->company = $company;

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
    
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * Add user
     *
     * @param \Pequiven\SEIPBundle\Entity\User $user
     *
     * @return MasterConnection
     */
    public function addUser(\Pequiven\SEIPBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Pequiven\SEIPBundle\Entity\User $user
     */
    public function removeUser(\Pequiven\SEIPBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }
}