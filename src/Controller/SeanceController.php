<?php

namespace App\Controller;

use DateTime;
use App\Entity\Seance;
use DateTimeImmutable;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/seance")
 */
class SeanceController extends AbstractController
{
    /**
     * @Route("/seancefilm", name="seance_index", methods={"GET"})
     */
    public function index(SeanceRepository $seanceRepository): Response
    {
        return $this->render('seance/index.html.twig', [
            'seances' => $seanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newseance", name="newSeance")
     * @Route("/updateS/{id}", name="updateSeance")
     */
    public function v2edit(Seance $seance = null, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator)
    {
        $entityManager = $doctrine->getManager();

        if (!$seance) {
            $seance = new Seance;
        }
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$seance->getId()) {
                $seance->setCreatedAt(new \DateTimeImmutable("now"));
            }
            $seance->setUpdatedAt(new DateTime("now"));
            $seance = $form->getData();


            $entityManager->persist($seance);
            $entityManager->flush();


            return $this->redirectToRoute('listingSeance');
        }

        $errors = $validator->validate($seance);

        return $this->renderForm('seance/newSeance.html.twig', [
            'form' => $form,
            'seance' => $seance->getId(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/listingSeances", name="listingSeance")
     * @Route("/seanceInfo/{id}" , name="seanceInfo")
     */
    public function listing(ManagerRegistry $doctrine, $id = null): Response
    {
        $seances =  $doctrine->getManager()->getRepository(Seance::class)->findAll();

        if (!isset($seances)) {
            return $this->redirectToRoute("listingSeance");
        } else {

            return $this->render("seance/listingSeances.html.twig", ["seances" => $seances]);
        }
        if (isset($id)) {
            $seance = $doctrine->getManager()->getRepository(Seance::class)->find($id);
            return $this->render("seance/seanceInfo.html.twig", ["seance" => $seance]);
        }
    }



    /**
     * @Route("/deleteS/{id}" , name="deleteSeance")
     */
    public function deleteS(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();
        //----------Delete----------

        if (isset($id)) {
            $seance = $entityManager->getRepository(Seance::class)->find($id);
            $entityManager->remove($seance);
            $entityManager->flush();
        }
        return $this->redirectToRoute('listingSeance');
    }
}
