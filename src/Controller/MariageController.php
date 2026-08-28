<?php

namespace App\Controller;

use App\Entity\Mariage;
use App\Form\MariageType;
use App\Repository\MariageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MariageController extends AbstractController
{
    #[Route('/admin/mariage/ajouter', name: 'app_admin_mariage_ajouter', methods: ['GET', 'POST'])]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');

        $mariage = new Mariage();

        $individu1Id = $request->query->get('individu1');
        $individu2Id = $request->query->get('individu2');

        if ($individu1Id) {
            $mariage->setIndividu1($em->getRepository(\App\Entity\Individu::class)->find($individu1Id));
        }
        if ($individu2Id) {
            $mariage->setIndividu2($em->getRepository(\App\Entity\Individu::class)->find($individu2Id));
        }

        $form = $this->createForm(MariageType::class, $mariage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($mariage);
            $em->flush();

            $this->addflash('success', 'Mariage enregistré.');

            return $this->redirectToRoute('app_admin_individu_modifier', ['id' => $mariage->getIndividu1()->getId()]);
        }

        return $this->render('individu/mariage_edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Ajouter un mariage',
            'routecancel' => 'app_individu_list',
            'form' => $form,
        ]);
    }

    #[Route('/admin/mariage/modifier/{id}', name: 'app_admin_mariage_modifier', methods: ['GET', 'POST'])]
    public function modifier(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');

        $mariage = $em->getRepository(Mariage::class)->find($id);
        if (!$mariage) {
            $this->addflash('error', 'Mariage non trouvé.');

            return $this->redirectToRoute('app_individu_list');
        }

        $form = $this->createForm(MariageType::class, $mariage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addflash('success', 'Mariage modifié.');

            return $this->redirectToRoute('app_admin_individu_modifier', ['id' => $mariage->getIndividu1()->getId()]);
        }

        return $this->render('individu/mariage_edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Modifier le mariage',
            'routecancel' => 'app_individu_list',
            'form' => $form,
        ]);
    }

    #[Route('/admin/mariage/supprimer/{id}', name: 'app_admin_mariage_supprimer')]
    public function supprimer(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');

        $mariage = $em->getRepository(Mariage::class)->find($id);
        if (!$mariage) {
            $this->addflash('error', 'Mariage non trouvé.');

            return $this->redirectToRoute('app_individu_list');
        }

        $individu1Id = $mariage->getIndividu1()->getId();

        $em->remove($mariage);
        $em->flush();

        $this->addflash('success', 'Mariage supprimé.');

        return $this->redirectToRoute('app_admin_individu_modifier', ['id' => $individu1Id]);
    }
}
