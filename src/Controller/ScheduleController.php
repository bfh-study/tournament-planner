<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\Team;
use App\Entity\Tournament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ScheduleController extends Controller {

    /**
     * @Route("/schedule/show/{tournament}", name="schedule_show", defaults={"tournament": 0})
     */
    public function show(Request $request, Security $security, $tournament) {
        $formEntity = new \StdClass();
        $formEntity->tournament = null;

        if (isset($tournament) && $tournament > 0) {
            $repoTournament = $this->getDoctrine()->getRepository(Tournament::class);
            $formEntity->tournament = $repoTournament->find($tournament);
            if ($formEntity->tournament != null && $formEntity->tournament->getCreator() != $security->getUser()) {
                return $this->render('schedule/msg-error.html.twig',
                    array ('msg' => 'Can not find schedule.', 'tournament' => $formEntity->tournament)
                );
            }
        }

        $form = $this->createSearchForm($formEntity, $security->getUser(), 'Show Schedule');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() || isset($tournament) && $tournament > 0){
            $repo = $this->getDoctrine()->getRepository(Schedule::class);
            $scheduleList = $repo->findBy(array('tournament' => $formEntity->tournament ));
            return $this->render('schedule/showSchedule.html.twig', array(
                'form' => $form->createView(),
                'scheduleList' => $scheduleList, 
                'tournament' => $formEntity->tournament
            ));
        }

        return $this->render('schedule/createSchedule.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/schedule/create", name="schedule_create")
     */
    public function create(Request $request, Security $security){
        $formEntity = new \StdClass();
        $formEntity->tournament = null;

        $form = $this->createSearchForm($formEntity, $security->getUser(), 'Create Schedule');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if (count($formEntity->tournament->getSchedules()) > 0) {
                return $this->render('schedule/msg-error.html.twig',
                    array ('msg' => 'Can not create schedule, it already exists.', 'tournament' => $formEntity->tournament)
                );
            }
            $this->generateSchedule($formEntity->tournament);
        }

        return $this->render('schedule/createSchedule.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * helper function
     */
    private function generateSchedule(Tournament $tournament){
        $teams = $tournament->getTeams()->getValues();
        // add dummy on the top, workaround for algo...
        array_unshift($teams, new Team(true));
        
        $groupSize = count($teams);
        $scheduleList = array();
        
        if($groupSize % 2 == 0){
            $dummyTeam = new Team(true);
            $dummyTeam->setName('oddDummy');
            array_push($teams, $dummyTeam);
            $groupSize++;
        }

        $arrayCounter = 0;
        $n = $groupSize -2;
        $gameNumber = 1;
        $fieldCounter = 0;
        $startDate = clone $tournament->getDate();
        for($i=1;$i<=$groupSize-2; $i++){
            $home = $teams[$groupSize-1];
            $away = $teams[$i];

            if($i%2 != 0){
                $tmp = $away;
                $away = $home;
                $home = $tmp;
            }

            if(!($home->getName() == 'oddDummy' && $home->isDummy() xor $away->getName() == 'oddDummy' && $away->isDummy())) {
                $this->checkFields($tournament->getFields(), $fieldCounter);
                if($gameNumber !=1)
                    $this->calcStartTime($tournament,$startDate, $fieldCounter);
                $schedule = new Schedule($tournament);
                $schedule->setGameNumber($gameNumber);
                $schedule->setField($fieldCounter);
                $schedule->setDate($startDate);
                $schedule->setHomeTeam($home);
                $schedule->setAwayTeam($away);
                $this->save($schedule);
                array_push($scheduleList, $schedule);
                $arrayCounter++;
                $gameNumber++;
            }

            for($k=1; $k <= (($groupSize/2)-1); $k++){

                if(($i-$k)<0){
                    $a = $n + ($i - $k);
                } else {
                    $a = (($i - $k) % $n);
                    $a = ($a == 0) ? $n : $a;
                }
                $away = $teams[$a];

                $h = (($i+$k) % $n);
                $h = ($h == 0) ? $n : $h;

                $home = $teams[$h];

                if(($k%2)==0){
                    $tmp = $away;
                    $away = $home;
                    $home = $tmp;
                }


                $this->checkFields($tournament->getFields(), $fieldCounter);
                if($gameNumber !=1)
                    $this->calcStartTime($tournament, $startDate, $fieldCounter);
                $schedule = new Schedule($tournament);
                $schedule->setGameNumber($gameNumber);
                $schedule->setField($fieldCounter);
                $schedule->setDate($startDate);
                $schedule->setHomeTeam($home);
                $schedule->setAwayTeam($away);
                $this->save($schedule);
                array_push($scheduleList, $schedule);
                $arrayCounter++;
                $gameNumber++;

            }
        }

        if($tournament->hasBackround() == true){
            foreach($scheduleList as $row){
                $this->checkFields($tournament->getFields(), $fieldCounter);
                $this->calcStartTime($tournament, $startDate, $fieldCounter);
                $schedule = new Schedule($tournament);
                $schedule->setGameNumber($gameNumber);
                $schedule->setField($fieldCounter);
                $schedule->setDate($startDate);
                $schedule->setHomeTeam($row->getAwayTeam());
                $schedule->setAwayTeam($row->getHomeTeam());
                $this->save($schedule);
                array_push($scheduleList, $schedule);
                $arrayCounter++;
                $gameNumber++;
            }
        }
    }

    private function checkFields($fields, &$fieldCounter){
        if($fieldCounter == $fields){
            $fieldCounter = 1;
        } else {
            $fieldCounter++;
        }
    }

    private function calcStartTime(Tournament $tournament, \DateTime $startDate, $fieldCounter){
        if($fieldCounter == 1){
            $startDate->add(
                new \DateInterval("PT".($tournament->getDuration() + $tournament->getInterruption())."M")
            );
        }
    }

    private function save(Schedule $schedule){
        if (!($schedule->getHomeTeam()->isDummy() || $schedule->getAwayTeam()->isDummy())) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($schedule);
            $em->flush();
        }
    }

    private function createSearchForm($entity, $user, $label) {
        $repo = $this->getDoctrine()->getRepository(Tournament::class);
        return $this->createFormBuilder($entity)
            ->add('tournament', EntityType::class, array(
                'class' => Tournament::class,
                'choice_label' => 'name',
                'multiple' => false,
                'data' => $repo->findAllActiveTournamentsByUser($user)
            ))
            ->add('generate', SubmitType::class, array(
                'label' => $label,
            ))
            ->getForm();
    }
}
