<?php
namespace App\Model;


use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;

class StandingCalculator
{
    /**
     * @var array
     */
    private $games;

    /**
     * @var ArrayCollection
     */
    private $standings;

    public function __construct(array $games)
    {
        $this->games = $games;
        $this->standings = new ArrayCollection();
    }

    public function calcStandings() {
        foreach ($this->games as $game) {
            $goalHome = $game->getGoalHome();
            $goalAway = $game->getGoalAway();
            $homePoints = 0;
            $awayPoints = 0;
            if ($goalHome > $goalAway) {
                $homePoints = 3;
            } elseif ($goalHome < $goalAway) {
                $awayTeam = 3;
            } else {
                $homePoints = 1;
                $awayPoints = 1;
            }
            $this->searchTeamAndAddResult($game->getHomeTeam(), $homePoints, $goalHome, $goalAway);
            $this->searchTeamAndAddResult($game->getAwayTeam(), $awayPoints, $goalAway, $goalHome);
        }

        return $this->standings->toArray();
    }

    private function searchTeamAndAddResult(Team $team, $points, $goalsFor, $goalsAgainst) {
        $standing = null;
        //search
        foreach ($this->standings as $val) {
            if ($val->getTeam() === $team) {
                $standing = $val;
                break;
            }
        }
        if (is_null($standing)) {
            $standing = new Standing($team);
            $this->standings->add($standing);
        }
        $standing->addPoints($points);
        $standing->addDiff($goalsFor - $goalsAgainst);
        $standing->addGoalFor($goalsFor);
        $standing->addGoalAgainst($goalsAgainst);
    }
}
