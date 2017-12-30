<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function index() {
        return $this->render('base.html.twig');
    }
}
