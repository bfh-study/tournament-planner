<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\Tournament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        if($form->isSubmitted() && $form->isValid()){
            $standings = array();
            $repo = $this->getDoctrine()->getRepository(Team::class);
            $standings = $repo->findBy(array('tournament' => $formEntity->tournament));

            // Calculate goal differenz and points per team
            foreach($standings as $row){
                $row->setDifferenz($row->getGoalsFor()-$row->getGoalsAgainst());
                $row->setPoints(($row->getWon()*3)+($row->getDrawn()*1));
            }

            // Sort table, first point, goalDifferenz, goalsFor, goalsAgainst
            foreach($standings as $key => $row){
                $array_points[$key] = $row->getPoints();
                $array_differenz[$key] = $row->getDifferenz();
                $array_goalsFor[$key] = $row->getGoalsFor();
                $array_goalAgainst[$key] = $row->getGoalsAgainst();
            }
            array_multisort($array_points, SORT_DESC, $array_differenz, SORT_ASC, $array_goalsFor, SORT_DESC, $array_goalAgainst, SORT_ASC,  $standings);

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
