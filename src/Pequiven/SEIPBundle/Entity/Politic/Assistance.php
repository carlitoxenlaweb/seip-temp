<?php

namespace Pequiven\SEIPBundle\Entity\Politic;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CONTROS DE ASISTENCIA DE REUNIONES
 * @author Victor Tortolero vart10.30@gmail.com
 * @ORM\HasLifecycleCallbacks()
 */
class Assistance {

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
    private $user;

    /**
     * @ManyToOne(targetEntity="Pequiven\SEIPBundle\Entity\Politic\Meeting", inversedBy="assistances")
     * @JoinColumn(name="meeting_id", referencedColumnName="id")
     * */
    private $meeting;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @param \Pequiven\SEIPBundle\Entity\User $user
     * @return \Pequiven\SEIPBundle\Entity\Politic\Assistance
     */
    function setUser(\Pequiven\SEIPBundle\Entity\User $user) {
        $this->user = $user;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * 
     * @param \Pequiven\SEIPBundle\Entity\Politic\Meeting $metting
     * @return \Pequiven\SEIPBundle\Entity\Politic\Assistance
     */
    function setMeeting(Meeting $metting) {
        $this->meeting = $metting;
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getMeeting() {
        return $this->meeting;
    }

}
