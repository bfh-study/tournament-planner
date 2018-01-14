<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;

class ScheduleController extends Controller {

    /**
     * @Route("/schedule/show", name="schedule_show")
     */
    public function show(){
        $repo = $this->getDoctrine()->getRepository(Schedule::class);
        $scheduleList = $repo->findSchedule();

        return $this->render('schedule/showSchedule.html.twig', array(
            'scheduleList' => $scheduleList,
        ));
    }

    /**
     * @Route("/schedule/create", name="schedule_create")
     */
    public function create(Request $reguest, Security $security){
        $schedule = new Schedule($security->getUser());

        $form = $this->createFormBuilder($schedule)
            ->add('generate', SubmitType::class, array(
                'label' => 'Create Schedule',
            ))
            ->getForm();
        
        $form->handleRequest($reguest);

        if($form->isSubmitted()){
            $teams = $this->generateTeams();
            $date = new \DateTime('2018-01-16 18:00');
            $this->generateSchedule($teams, $date, 5, 20, 2, false);
        }

        return $this->render('schedule/createSchedule.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * helper function
     */
    private function generateTeams(){
        $teamList = array('FC Basel',
                            'BSY YoungBoys',
                            'FC Zürich',
                            'AC Milan',
                            'AS Roma',
                            'SSC Napoli');

        return $teamList;
    }

    private function generateSchedule($teams, $date, $duration, $interruption, $fields, $hasBackround){
        // add dummy on the top, workaround for algo...
        $dummy = 'dummy';
        array_unshift($teams, $dummy);
        
        $groupSize = count($teams);
        $scheduleList = array();
        
        if($groupSize % 2 == 0){
            $oddDummy = "oddDummy";
            array_push($teams, $oddDummy);
            $groupSize++;
        }

        $arrayCounter = 0;
        $n = $groupSize -2;
        $gameNumber = 1;
        $fieldCounter;

        for($i=1;$i<=$groupSize-2; $i++){
            $home = $teams[$groupSize-1];
            $away = $teams[$i];

            if($i%2 != 0){
                $tmp = $away;
                $away = $home;
                $home = $tmp;
            }

            if(!($home == 'oddDummy' xor $away == 'oddDummy')) {
                $this->checkFields($fields, $fieldCounter);
                if($gameNumber !=1) $this->calcStartTime($duration, $interruption, $date, $fieldCounter);
                $startDate = $date->format("d.m.Y H:i");
                $schedule = new Schedule();
                $schedule->setGameNumber = $gameNumber;
                $schedule->setField = $fieldCounter;
                $schedule->setDate = $startDate;
                $schedule->setHomeTeam = $home;
                $schedule->setAwayTeam = $away;
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


                $this->checkFields($fields, $fieldCounter);
                if($gameNumber !=1) $this->calcStartTime($duration, $interruption, $date, $fieldCounter);
                $startDate = $date->format("d.m.Y H:i");
                $schedule = new Schedule();
                $schedule->setGameNumber = $gameNumber;
                $schedule->setField = $fieldCounter;
                $schedule->setDate = $startDate;
                $schedule->setHomeTeam = $home;
                $schedule->setAwayTeam = $away;
                $this->save($schedule);
                array_push($scheduleList, $schedule);
                $arrayCounter++;
                $gameNumber++;

            }
        }

        if($hasBackround == 1){
            foreach($schedule as $row){
                $this->checkFields($fields, $fieldCounter);
                $this->calcStartTime($duration, $interruption, $date, $fieldCounter);
                $startDate = $date->format("d.m.Y H:i");
                $schedule = new Schedule();
                $schedule->setGameNumber = $gameNumber;
                $schedule->setField = $fieldCounter;
                $schedule->setDate = $startDate;
                $schedule->setHomeTeam = $away;
                $schedule->setAwayTeam = $home;
                $this->save($schedule);
                array_push($scheduleList, $schedule);
                $arrayCounter++;
                $gameNumber++;
            }
        }
    }

    private function checkFields(&$fields, &$fieldCounter){
        if($fieldCounter == $fields){
            $fieldCounter = 1;
        } else {
            $fieldCounter++;
        }

        return $fieldCounter;
    }

    private function calcStartTime($duration, $interruption, $date, $fieldCounter){

        if($fieldCounter == 1){
            $m = $duration+$interruption;
            $n = "PT".$m."M";
            return $date->add(new \DateInterval($n));
        } else {
            return $date;
        }
    }

    private function save(Schedule $schedule){
        $em = $this->getDoctrine()->getManager();
        $em->persist($schedule);
        $em->flush();
    }
}
