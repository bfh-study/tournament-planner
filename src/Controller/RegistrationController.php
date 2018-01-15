<?php

namespace App\Controller;


use App\Entity\Team;
use App\Entity\Tournament;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    const REGISTRATION_COOKIE_NAME = 'publicRegistration';

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('dashboard');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }


    /**
     * @Route(
     *     "/register/team/{hash}",
     *     name="public_team_registration",
     *     requirements={
     *         "hash": "^[a-f0-9]{64}$"
     *     }
     * )
     */
    public function publicTeamRegisterAction(Request $request, $hash)
    {
        $repo = $this->getDoctrine()->getRepository(Tournament::class);
        $tournament = $repo->findAllTournamentsByHash($hash);

        if (is_null($tournament)) {
            return $this->render('registration/public-team-register-msg.html.twig',
                array('msg' => 'No active tournament found', 'type' => 'error')
            );
        } else {
            if ($request->cookies->get(self::REGISTRATION_COOKIE_NAME) == true) {
                return $this->render('registration/public-team-register-msg.html.twig',
                    array('msg' => 'Your team is already registered!', 'type' => 'success')
                );
            }

            $response = new Response();
            $team = new Team();
            $form = $this->generateTeamRegistrationForm($team);

            $form->handleRequest($request);
            $isFormSubmittedAndValid = $form->isSubmitted() && $form->isValid();
            if ($isFormSubmittedAndValid) {
                $team = $form->getData();
                $team->setTournament($tournament);

                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($team);
                    $em->flush();
                    $response->headers->setCookie(
                        new Cookie(self::REGISTRATION_COOKIE_NAME, true, $tournament->getDate()->getTimestamp())
                    );
                } catch (UniqueConstraintViolationException $exception) {
                    return $this->render(
                        'registration/public-team-register.html.twig',
                        array('form' => $form->createView(), 'isFormSubmittedAndValid' => false, 'error' => true),
                        $response
                    );
                }
            }

            return $this->render(
                'registration/public-team-register.html.twig',
                array('form' => $form->createView(), 'isFormSubmittedAndValid' => $isFormSubmittedAndValid, 'error' => false),
                $response
            );
        }
    }

    private function generateTeamRegistrationForm($team) {
        return $this->createFormBuilder($team)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Add Team'))
            ->getForm();
    }


}
