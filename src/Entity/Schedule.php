<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="schedules")
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule {

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="schedules")
     * @ORM\JoinColumn(name="tournament", referencedColumnName="id")
     */
    private $tournament;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=3)
     */
    private $gameNumber;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $field;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="home_team_name", referencedColumnName="name", nullable=false),
     *     @ORM\JoinColumn(name="home_team_tournament_id", referencedColumnName="tournament_id", nullable=false)
     * })
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="away_team_name", referencedColumnName="name", nullable=false),
     *     @ORM\JoinColumn(name="away_team_tournament_id", referencedColumnName="tournament_id", nullable=false)
     * })
     */
    private $awayTeam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goalHome;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goalAway;


    public function __construct(Tournament $tournament = null) {
        $this->tournament = $tournament;
    }

    /**
     * @return Tournament
     */
    public function getTournament() {
        return $this->tournament;
    }

    /**
     * @return \Datetime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param \Datetime $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return integer
     */
    public function getGameNumber()
    {
        return $this->gameNumber;
    }

    /**
     * @param integer $gameNumber
     */
    public function setGameNumber($gameNumber){
        $this->gameNumber = $gameNumber;
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
        if ($this->goalHome == null) {
            return 0;
        }
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
        if ($this->goalAway == null) {
            return 0;
        }
        return $this->goalAway;
    }

    /**
     * @param integer $goalAway
     */
    public function setGoalAway($goalAway){
        $this->goalAway = $goalAway;
    }
}
