<?php

namespace App\Controller;

use App\Entity\Films;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmsController extends AbstractController
{

    // public function index(): Response
    // {
    //     return $this->render('films/index.html.twig', [
    //         'controller_name' => 'FilmsController',
    //     ]);
    // }

    /**
     * @Route("/createfilms", name="createFilms")
     */
    public function createFilms(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $film = new Films();
        $film->setTitle('Loki');
        $film->setSynopsis('Nathan Drake, voleur astucieux et intrépide, ');
        $film->setGenre('Aventure');
        $film->setRealisateur('Jean Doe');
        $film->setDuree('150 minutes');

        $entityManager->persist($film);
        $entityManager->flush();

        return new Response('Hop prochain Films disponibles : ' . $film->getTitle());
    }


    /**
     * @Route("/listingFilms", name="listingFilms")
     */
    public function listing(ManagerRegistry $doctrine): Response
    {
        $films =  $doctrine->getManager()->getRepository(Films::class)->findAll();

        if (!isset($films)) {
            return $this->redirectToRoute("accueil");
        }
        return $this->render("navigation/films.html.twig", ["films" => $films]);
    }
}
