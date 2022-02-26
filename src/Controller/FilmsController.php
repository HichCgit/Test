<?php

namespace App\Controller;

use DateTime;
use App\Entity\Films;
use DateTimeImmutable;
use App\Form\FilmformType;
use App\Repository\FilmsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilmsController extends AbstractController
{
    // private $FR;
    // public function __construct(FilmsRepository $FR)
    // {
    //     $this->FR = $FR;
    // }


    public function index(Request $request, ManagerRegistry $doctrine, $id = null)
    {
        // $entityManager = $doctrine->getManager();
        // $isEditor = false;


        // //-----------Editer un film-------
        // if (isset($id)) {
        //     $films = $entityManager->getRepository(Films::class)->find($id);
        //     if (!isset($films)) {
        //         return $this->redirectToRoute('listingFilms');
        //     }
        //     $isEditor = true;
        // } else {
        //     $films = new Films;
        // }



        // //----------CREATION FORMULAIRE----------------------


        // $form = $this->createFormBuilder($films)
        //     ->add('Title', TextType::class)
        //     ->add('Synopsis', TextType::class)
        //     ->add('Genre', TextType::class)
        //     ->add('Realisateur', TextType::class)
        //     ->add('Duree', TextType::class)
        //     ->add('save', SubmitType::class, ['label' => 'Create Film'])
        //     ->getForm();

        // // ------------------ Envoie du Formulaire --------


        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {

        //     $films = $form->getData();


        //     $entityManager->persist($films);
        //     $entityManager->flush();


        //     return $this->redirectToRoute('accueil');
        // }

        // return $this->renderForm('films/createFilm.html.twig', [
        //     'form' => $form,
        //     'isEditor' => $isEditor
        // ]);
    }


    /**
     * @Route("/delete/{id}" , name="deleteF")
     */
    public function deleteF(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();
        //----------Delete----------

        if (isset($id)) {
            $film = $entityManager->getRepository(Films::class)->find($id);
            $entityManager->remove($film);
            $entityManager->flush();
        }
        return $this->redirectToRoute('listingFilms');
    }

    /**
     * @Route("/listingFilms", name="listingFilms")
     * @Route("/filmInfo/{id}" , name="filmInfo")
     */
    public function listing(ManagerRegistry $doctrine, $id = null): Response
    {
        $films =  $doctrine->getManager()->getRepository(Films::class)->findAll();

        if (!isset($films)) {
            return $this->redirectToRoute("listingFilms");
        } else {

            return $this->render("films/listingFilms.html.twig", ["films" => $films]);
        }
        if (isset($id)) {
            $film = $doctrine->getManager()->getRepository(Films::class)->find($id);
            return $this->render("films/filminfo.html.twig", ["film" => $film]);
        }
    }

    /**
     * @Route("/filmInfo/{id}" , name="filmInfo")
     */
    public function findOne(ManagerRegistry $doctrine, $id): Response
    {

        if (isset($id)) {
            $film = $doctrine->getManager()->getRepository(Films::class)->find($id);
            return $this->render("films/filminfo.html.twig", ["film" => $film]);
        }
    }
    /**
     * @Route("/ff", name="createFilms")
     * @Route("/updateF/{id}", name="updateF")
     */
    public function v2edit(Films $film = null, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator)
    {
        $entityManager = $doctrine->getManager();

        if (!$film) {
            $film = new Films;
        }
        $form = $this->createForm(FilmformType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$film->getId()) {
                $film->setCreatedAt(new \DateTimeImmutable("now"));
            }
            $film->setUpdatedAt(new DateTime("now"));
            $film = $form->getData();


            $entityManager->persist($film);
            $entityManager->flush();


            return $this->redirectToRoute('accueil');
        }

        $errors = $validator->validate($film);

        return $this->renderForm('films/createFilm.html.twig', [
            'form' => $form,
            'isEditor' => $film->getId(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/AjoutFilm", name="AjoutFilm")
     */

    // public function ajoutFilm(Request $request)
    // {
    //     $Titre = $request->query->get('TitreFilm');
    //     $Synposis = $request->query->get('Synopsis');
    //     $Realisateur = $request->query->get('realisateur');
    //     $Genre = $request->query->get('genre');
    //     $Duree = $request->query->get('duree');

    //     if ($request == true) {
    //         $this->FR->addFilm($Titre, $Synposis, $Realisateur, $Genre, $Duree);
    //         echo "wshhhhh";
    //     } else {
    //         echo "Probleme insertion Film";
    //     }
    //     return $this->redirectToRoute("accueil");
    // }

    /**
     * @Route("/createfilms", name="createFilms")
     */
    // public function createFilms(ManagerRegistry $doctrine): Response
    // {
    //     // $entityManager = $doctrine->getManager();

    //     // $film = new Films();
    //     // $film->setTitle('Loki');
    //     // $film->setSynopsis('Nathan Drake, voleur astucieux et intrépide, ');
    //     // $film->setGenre('Aventure');
    //     // $film->setRealisateur('Jean Doe');
    //     // $film->setDuree('150 minutes');

    //     // $entityManager->persist($film);
    //     // $entityManager->flush();

    //     // return new Response('Hop prochain Films disponibles : ' . $film->getTitle());

    //     return $this->render("formulaire/FormAjoutFilm.html.twig");
    // }
}
