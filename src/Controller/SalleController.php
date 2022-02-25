<?php

namespace App\Controller;

use DateTime;
use App\Entity\Seance;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalleController extends AbstractController
{
    /**
     * @Route("/salle", name="salle")
     */
    public function index(): Response
    {
        return $this->render('salle/index.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }

    /**
     * @Route("/newseance", name="newSeance")
     * @Route("/updateS/{id}", name="updateSeance")
     */
    public function v2edit(Seance $salle = null, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator)
    {
        $entityManager = $doctrine->getManager();

        if (!$salle) {
            $salle = new Seance;
        }
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$salle->getId()) {
                $salle->setStatus;
            }
            $salle->setUpdatedAt(new DateTime("now"));
            $salle = $form->getData();


            $entityManager->persist($salle);
            $entityManager->flush();


            return $this->redirectToRoute('accueil');
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
     * @Route("/salleInfo/{id}" , name="salleInfo")
     */
    public function listing(ManagerRegistry $doctrine, $id = null): Response
    {
        $salles =  $doctrine->getManager()->getRepository(Salle::class)->findAll();

        if (!isset($salles)) {
            return $this->redirectToRoute("accueil");
        } else {

            return $this->render("salle/listingSalle.html.twig", ["salles" => $salles]);
        }
        if (isset($id)) {
            $salle = $doctrine->getManager()->getRepository(Salle::class)->find($id);
            return $this->render("salle/salleInfo.html.twig", ["salle" => $salle]);
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
