<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\Tournament;
use App\Model\StandingCalculator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class StandingController extends Controller {

    /**
     * @Route("/standing/show", name="standing")
     */
    public function show(Request $request, Security $security)
    {
        $formEntity = new \StdClass();
        $formEntity->tournament = null;

        $form = $this->createSearchForm($formEntity, $security->getUser(), 'Show Standing');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $repo = $this->getDoctrine()->getRepository(Schedule::class);
            $schedules = $repo->findBy(array('tournament' => $formEntity->tournament));

            $calc = new StandingCalculator($schedules);
            $standings = $calc->calcStandings();

            if (count($standings) > 0) {
                // Sort table, first point, goalDifferenz, goalsFor, goalsAgainst
                foreach ($standings as $key => $row) {
                    $array_points[$key] = $row->getPoints();
                    $array_differenz[$key] = $row->getDiff();
                    $array_goalsFor[$key] = $row->getGoalsFor();
                    $array_goalAgainst[$key] = $row->getGoalsAgainst();
                }
                array_multisort($array_points, SORT_DESC, $array_differenz, SORT_ASC, $array_goalsFor, SORT_DESC, $array_goalAgainst, SORT_ASC, $standings);

            }
            return $this->render('standings/standings.html.twig', array(
                'form' => $form->createView(),
                'standings' => $standings,
                'tournament' => $formEntity->tournament,
            ));
        }

        return $this->render('schedule/createSchedule.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/standing/update/{tournamentId}/{gameNumber}",
     *     name="standing_update_form",
     *     requirements={
     *         "tournamentId": "\d+",
     *         "gameNumber": "\d+"
     *     }
     * )
     */
    public function showUpdateResultForm(Request $request, $tournamentId, $gameNumber)
    {
        $repo = $this->getDoctrine()->getRepository(Schedule::class);
        $schedules = $repo->findBy(array('tournament' => $tournamentId, 'gameNumber' => $gameNumber));
        if (count($schedules) == 0) {
            return $this->render('index.html.twig');
        }
        $schedule = $schedules[0];
        $form = $this->updateResultForm($schedule);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $schedule = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($schedule);
            $em->flush();
        }

        return $this->render('schedule/update-schedule.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function updateResultForm(Schedule $schedule) {
        return $this->createFormBuilder($schedule)
            ->add('goalHome', IntegerType::class, array('data' => 0))
            ->add('goalAway', IntegerType::class, array('data' => 0))
            ->add('generate', SubmitType::class, array('label' => 'Update Result',))
            ->getForm();
    }

    private function createSearchForm($entity, $user, $label) {
        $repo = $this->getDoctrine()->getRepository(Tournament::class);
        return $this->createFormBuilder($entity)
            ->add('tournament', EntityType::class, array(
                'class' => Tournament::class,
                'choice_label' => 'name',
                'multiple' => false,
                'data' => $repo->findBy(array('creator' => $user))
            ))
            ->add('generate', SubmitType::class, array(
                'label' => $label,
            ))
            ->getForm();
    }
}
