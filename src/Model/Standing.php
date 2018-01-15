<?php
/**
 * Created by IntelliJ IDEA.
 * User: samuel
 * Date: 15.01.18
 * Time: 21:41
 */

namespace App\Model;


use App\Entity\Team;

class Standing
{
    /**
     * @var Team
     */
    private $team;

    private $played;

    private $won;

    private $drawn;

    private $lost;

    private $points;

    private $diff;

    private $goalFor;

    private $goalAgainst;

    public function __construct(Team $team)
    {
        $this->team = $team;
        $this->played = 0;
        $this->won = 0;
        $this->drawn = 0;
        $this->lost = 0;
        $this->points = 0;
        $this->diff = 0;
        $this->goalFor = 0;
        $this->goalAgainst = 0;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param $points integer
     */
    public function addPoints($points)
    {
        $this->played++;
        if ($points == 3) {
            $this->won++;
        } elseif ($points == 1) {
            $this->drawn++;
        } else {
            $this->lost++;
        }

        $this->points += $points;
    }

    /**
     * @param $diff integer
     */
    public function addDiff($diff)
    {
        $this->diff += $diff;
    }

    /**
     * @param $goals integer
     */
    public function addGoalFor($goals)
    {
        $this->goalFor += $goals;
    }

    /**
     * @param $goals integer
     */
    public function addGoalAgainst($goals)
    {
        $this->goalAgainst += $goals;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getDiff()
    {
        return $this->diff;
    }

    /**
     * @return int
     */
    public function getGoalsFor()
    {
        return $this->goalFor;
    }

    /**
     * @return int
     */
    public function getGoalsAgainst()
    {
        return $this->goalAgainst;
    }

    /**
     * @return int
     */
    public function getPlayed()
    {
        return $this->played;
    }

    /**
     * @return int
     */
    public function getWon()
    {
        return $this->won;
    }

    /**
     * @return int
     */
    public function getDrawn()
    {
        return $this->drawn;
    }

    /**
     * @return int
     */
    public function getLost()
    {
        return $this->lost;
    }


}
