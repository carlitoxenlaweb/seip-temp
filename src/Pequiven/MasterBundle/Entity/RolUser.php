<?php

namespace Pequiven\MasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
 
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user_user_rol")
 */
class RolUser
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Pequiven\SEIPBundle\Entity\User", inversedBy="groups", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Pequiven\MasterBundle\Entity\Rol", inversedBy="roleUser", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id", nullable=false)
     */
    protected $rol;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Pequiven\SEIPBundle\Entity\CEI\Company", inversedBy="roleUser", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     */
    protected $company;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->user->getId().'~'.$this->rol->getId().'~'.$this->company->getId();
    }

    /**
     * Set user
     *
     * @param \Pequiven\SEIPBundle\Entity\User $user
     *
     * @return RolUser
     */
    public function setUser(\Pequiven\SEIPBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Pequiven\SEIPBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set rol
     *
     * @param \Pequiven\MasterBundle\Entity\Rol $rol
     *
     * @return RolUser
     */
    public function setRol(\Pequiven\MasterBundle\Entity\Rol $rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \Pequiven\MasterBundle\Entity\Rol
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set company
     *
     * @param \Pequiven\SEIPBundle\Entity\CEI\Company $company
     *
     * @return RolUser
     */
    public function setCompany(\Pequiven\SEIPBundle\Entity\CEI\Company $company)
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
        // return (sprintf("%s %s (%s)", $this->user->getFirstName(), $this->user->getLastName(), $this->user->getNumPersonal())).",".
        //        ($this->company->getAlias()?:'-').",".
        //        ($this->rol->getDescription()?:'-');
        return "Asignar Rol";
    }
}
