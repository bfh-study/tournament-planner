<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function indexAction() {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $userList = $repo->findBy(array('isActive' => true), array('email' => 'asc'));
        
        return $this->render('admin/index.html.twig', array('userList' => $userList));
    }

    /**
     * @Route("/admin/change/{id}", name="admin_change_rights")
     */
    public function changeRightsAction($id) {
        $function = function(User $user) {
            if (in_array(User::ROLE_USER, $user->getRoles(), true)) {
                $user->setRole(User::ROLE_ADMIN);
            } else {
                $user->setRole(User::ROLE_USER);
            }
        };
        $this->doAdminStuff($id, $function);

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/admin/deactivate/{id}", name="admin_deactivate_user")
     */
    public function deactivateUserAction($id) {
        $function = function (User $user) {
            $user->setIsActive(false);
        };
        $this->doAdminStuff($id, $function);

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/admin/tournaments/show/{id}", name="admin_show_tournaments")
     */
    public function showTournamentsAction($id) {
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repoUser->find($id);
        $tournamentsList = array();
        if (!is_null($user)) {
            $repoTournament = $this->getDoctrine()->getRepository(Tournament::class);
            $tournamentsList = $repoTournament->findAllTournamentsByUser($user);
        }

        return $this->render(
            'admin/show-tournaments.html.twig',
            array('tournamentList' => $tournamentsList, 'user' => $user)
        );
    }

    private function doAdminStuff($id, $task) {
        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repoUser->find($id);
        if (!is_null($user)) {
            $task($user);
            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();
        }
    }
}
