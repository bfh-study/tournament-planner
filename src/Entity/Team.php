<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="teams")
 * @ORM\Entity()
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tournament", inversedBy="teams")
     */
    private $tournament;

    /**
     * @var boolean
     */
    private $isDummy;

    public function __construct($isDummy = false) {
        $this->isDummy = $isDummy;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return Tournament
     */
    public function getTournament() {
        return $this->tournament;
    }

    /**
     * @param Tournament $tournament
     */
    public function setTournament($tournament) {
        $this->tournament = $tournament;
    }

    /**
     * @return bool
     */
    public function isDummy() {
        return $this->isDummy;
    }

    /**
     * @return integer
     */
    public function getPlayed(){
        return $this->played;
    }

    /**
     * @param integer $played
     */
    public function setPlayed($played){
        $this->played = $played;
    }

    /**
     * @return integer
     */
    public function getWon(){
        return $this->won;
    }

    /**
     * @param integer $won
     */
    public function setWon($won){
        $this->won = $won;
    }

    /**
     * @return integer
     */
    public function getDrawn(){
        return $this->drawn;
    }

    /**
     * @param integer $drawn
     */
    public function setDrawn($drawn){
        $this->drawn = $drawn;
    }

    /**
     * @return integer
     */
    public function getLost(){
        return $this->lost;
    }

    /**
     * @param integer $lost
     */
    public function setLost($lost){
        $this->lost = $lost;
    }

    /**
     * @return integer
     */
    public function getGoalsFor(){
        return $this->goalsFor;
    }

    /**
     * @param integer $goalsFor
     */
    public function setGoalsFor($goalsFor){
        $this->goalsFor = $goalsFor;
    }

    /**
     * @return integer
     */
    public function getGoalsAgainst(){
        return $this->goalsAgainst;
    }

    /**
     * @param integer $goalsAgainst
     */
    public function setGoalsAgainst($goalsAgainst){
        $this->goalsAgainst = $goalsAgainst;
    }

    /**
     * @return integer
     */
    public function getDifferenz(){
        return $this->differenz;
    }

    /**
     * @param integer $differenz
     */
    public function setDifferenz($differenz){
        $this->differenz = $differenz;
    }

    /**
     * @return integer
     */
    public function getPoints(){
        return $this->points;
    }

    /**
     * @param integer $points
     */
    public function setPoints($points){
        $this->points = $points;
    }
}
