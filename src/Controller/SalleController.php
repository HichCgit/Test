<?php

namespace App\Controller;

use DateTime;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Form\SalleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/salle", name="salle")
 */
class SalleController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('salle/index.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }

    /**
     * @Route("/newSalle", name="newSalle")
     * @Route("/updateSalle/{id}", name="updateSalle")
     */
    public function v2edit(Salle $salle = null, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator)
    {
        $entityManager = $doctrine->getManager();

        if (!$salle) {
            $salle = new Salle;
        }
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // if (!$salle->getId()) {
            //     $salle->setStatus(true);
            // }
            // $salle->setStatus());
            $salle = $form->getData();


            $entityManager->persist($salle);
            $entityManager->flush();


            return $this->redirectToRoute('sallelistingSalle');
        }

        $errors = $validator->validate($salle);

        return $this->renderForm('salle/newSalle.html.twig', [
            'form' => $form,
            'salle' => $salle->getId(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/listingSalle", name="listingSalle")
     */
    public function listing(ManagerRegistry $doctrine, $id = null): Response
    {
        $salles =  $doctrine->getManager()->getRepository(Salle::class)->findAll();

        if (!isset($salles)) {
            return $this->redirectToRoute("sallelistingSalle");
        } else {

            return $this->render("salle/listingSalles.html.twig", ["salles" => $salles]);
        }
    }



    /**
     * @Route("/deleteSalle/{id}" , name="deleteSalle")
     */
    public function deleteS(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();
        //----------Delete----------

        if (isset($id)) {
            $salle = $entityManager->getRepository(Salle::class)->find($id);
            $entityManager->remove($salle);
            $entityManager->flush();
        }
        return $this->redirectToRoute('listingSalle');
    }
}
