<?php

namespace App\Controller;

use App\Entity\SportType;
use App\Entity\Tournament;
use App\Repository\TournamentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TournamentController extends Controller
{
    /**
     * @Route("/tournament/show", name="tournament_show")
     */
    public function show(Security $security)
    {
        $repo = $this->getDoctrine()->getRepository(Tournament::class);
        $tournamentList = $repo->findAllTournamentsByUser($security->getUser());

        return $this->render('tournament/show.html.twig', array(
            'tournamentList' => $tournamentList,
        ));
    }

    /**
     * @Route("/tournament/create", name="tournament_create")
     */
    public function create(Request $request, Security $security)
    {
        $tournament = new Tournament($security->getUser());

        $form = $this->createFormBuilder($tournament)
            ->add('name', TextType::class)
            ->add('place', TextType::class)
            ->add('type', EntityType::class, array(
                'class' => SportType::class,
                'choice_label' => 'name',
                'multiple' => false,
            ))
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Tournament'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournament = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($tournament);
            $em->flush();
        }

        return $this->render('tournament/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
