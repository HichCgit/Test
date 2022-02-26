<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavController extends AbstractController
{

    /**
     * @Route("/", name="accueil")
     */

    public function accueil()
    {

        return $this->render("navigation/accueil.html.twig");
    }


    /**
     * @Route("/admin", name="Admin")
     */

    public function Admin()
    {

        return $this->render("admin.html.twig");
    }


    /**
     * @Route("/films", name="films")
     */

    // public function films()
    // {

    //     $films = [
    //         "Harry",
    //         "Spider",
    //         "Iron-man"
    //     ];

    //     return $this->render("navigation/films.html.twig", ["films" => $films]);
    // }


    /**
     * @Route("/redirect", name="redirect")
     */

    public function homeRedirect()
    {
        return $this->redirectToRoute("accueil");
    }
}
