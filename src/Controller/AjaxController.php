<?php
namespace App\Controller;

use App\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax/result", name="ajax_result")
     */
    public function getResultsOfTournamentsToday(Security $security) {
        $tournamentRepo = $this->getDoctrine()->getRepository(Tournament::class);
        $tournamentsToday = $tournamentRepo->findAllTodayTournamentsByUser($security->getUser());
        $returnList = array();
        foreach ($tournamentsToday as $t) {
            $tournament = new \StdClass();
            $tournament->id = $t->getId();
            $tournament->name = $t->getName();
            $tournament->results = array();
            foreach ($t->getSchedules() as $schedule) {
                $gameResult = new \StdClass();
                $gameResult->name = $schedule->getHomeTeam()->getName().':'.$schedule->getAwayTeam()->getName();
                $gameResult->goalsHome = $schedule->getGoalHome();
                $gameResult->goalsAway = $schedule->getGoalAway();
                $tournament->results[] = $gameResult;
            }
            $returnList[] = $tournament;
        }

        return $this->json($returnList);
    }
}