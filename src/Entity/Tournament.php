<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tournaments")
 * @ORM\Entity(repositoryClass="App\Repository\TournamentRepository");
 * @ORM\HasLifecycleCallbacks
 */
class Tournament extends AuditBase
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="SportType")
     * @ORM\JoinColumn(name="sport_type_name", referencedColumnName="name")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $hash;

    /**
     * @ORM\Column(type="integer")
     */
    private $fields;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $interruption;

    /**
     * @ORM\Column(type="boolean")
     */
    private $backround;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="tournament")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="tournament")
     */
    private $schedules;

    /**
     * Tournament constructor.
     * @param UserInterface $creator
     */
    public function __construct(UserInterface $creator) {
        $this->creator = $creator;
        $this->teams = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return SportType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param SportType $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @ORM\PrePersist
     */
    public function createHashOnPrePersist($hash)
    {
        if (!isset($this->hash)) {
            $random = base64_encode(random_bytes(10));
            $this->hash = hash('ripemd256', $this->getName() . '.' . $random);
        }
    }
    
    /**    
     * @return integer
     */
    public function getFields(){
        return $this->fields;
    }

    /**
     * @param integer $fields
     */
    public function setFields($fields){
        $this->fields = $fields;
    }

    /**
     * @return integer
     */
    public function getDuration(){
        return $this->duration;
    }

    /**
     * @param integer $duration
     */
    public function setDuration($duration){
        $this->duration = $duration;
    }

    /**
     * @return integer
     */
    public function getInterruption(){
        return $this->interruption;
    }

    /**
     * @param integer $interruption
     */
    public function setInterruption($interruption){
        $this->interruption = $interruption;
    }

    /**
     * @return boolean
     */
    public function hasBackround(){
        return $this->backround;
    }

    /**
     * @param boolean $backround
     */
    public function setBackround($backround){
        $this->backround = $backround;
    }

    /**
     * @param ArrayCollection $teams
     */
    public function setTeams(ArrayCollection $teams)
    {
        $this->teams = $teams;
    }

    /**
     * @return ArrayCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @return ArrayCollection
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->date != null && $this->date > new \DateTime();
    }

    /**
     * @return bool
     */
    public function isToday() {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        if ($today->diff($this->date)->days == 0) {
            return true;
        }
        return false;
    }
}
