<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller {

    /**
     * @Route("/", name="index")
     */
    public function index() {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->redirectToRoute('dashboard');
        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/locale", name="locale")
     */
    public function locale(Request $request) {
        $redirectPath = $request->get('path');
        $parameters = $request->get('params');
        if (is_null($redirectPath))
            $redirectPath = 'index';
        if (is_null($parameters))
            $parameters = array();

        return $this->redirectToRoute($redirectPath, $parameters);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard() {
        return $this->render('index/dashboard.html.twig');
    }
}
