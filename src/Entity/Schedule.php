<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="schedule")
 * @ORM\Entity()
 */
class Schedule{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $gameNumber;

    /**
     * @ORM\Id
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $field;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="schedule")
     */
    private $homeTeam;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="schedule")
     */
    private $awayTeam;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalHome;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalAway;

    /**
     * @return integer
     */
    public function getGameNumber(){
        return $this->gameNumber;
    }

    /**
     * @param integer $gameNumber
     */
    public function setGameNumber($gameNumber){
        $this->gameNumber = $gameNumber;
    }

    /**
     * @return datetime
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * @param datetime $date
     */
    public function setDate($date){
        $this->date = $date;
    }

    /**
     * @return integer
     */
    public function getField(){
        return $this->field;
    }

    /**
     * @param integer $field
     */
    public function setField($field){
        $this->field = $field;
    }

    /**
     * @return Team
     */
    public function getHomeTeam(){
        return $this->homeTeam;
    }

    /**
     * @param Team $homeTeam
     */
    public function setHomeTeam($homeTeam){
        $this->homeTeam = $homeTeam;
    }

    /**
     * @return Team
     */
    public function getAwayTeam(){
        return $this->awayTeam;
    }

    /**
     * @param Team $awayTeam
     */
    public function setAwayTeam($awayTeam){
        $this->awayTeam = $awayTeam;
    }

    /**
     * @return integer
     */
    public function getGoalHome(){
        return $this->goalHome;
    }

    /**
     * @param integer $goalHome
     */
    public function setGoalHome($home){
        $this->goalHome = $home;
    }

    /**
     * @return integer
     */
    public function getGoalAway(){
        return $this->goalAway;
    }

    /**
     * @param integer $goalAway
     */
    public function setGoalAway($goalAway){
        $this->goalAway = $goalAway;
    }
}
