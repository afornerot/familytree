<?php

namespace App\Controller;

use App\Entity\Individu;
use App\Entity\Mariage;
use App\Form\IndividuType;
use App\Form\MariageType;
use App\Repository\IndividuRepository;
use App\Repository\MariageRepository;
use Bnine\FilesBundle\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndividuController extends AbstractController
{
    #[Route('/individu', name: 'app_individu_list')]
    public function list(IndividuRepository $individuRepository): Response
    {
        $individus = $individuRepository->findBy([], ['nomNaissance' => 'ASC']);

        return $this->render('individu/list.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Liste des Individus',
            'individus' => $individus,
        ]);
    }

    #[Route('/admin/individu/ajouter', name: 'app_admin_individu_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('INDIVIDU_CREATE');

        $individu = new Individu();

        $form = $this->createForm(IndividuType::class, $individu, ['isNew' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($individu);
            $em->flush();

            $this->addflash('success', 'Individu créé avec succès.');

            return $this->redirectToRoute('app_admin_individu_modifier', ['id' => $individu->getId()]);
        }

        return $this->render('individu/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Créer un individu',
            'routecancel' => 'app_individu_list',
            'mode' => 'ajouter',
            'form' => $form,
        ]);
    }

    #[Route('/admin/individu/modifier/{id}', name: 'app_admin_individu_modifier')]
    public function modifier(int $id, Request $request, EntityManagerInterface $em, FileService $fileService): Response
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');

        $individu = $em->getRepository(Individu::class)->find($id);
        if (!$individu) {
            $this->addflash('error', 'Individu non trouvé.');

            return $this->redirectToRoute('app_individu_list');
        }

        $fileService->init('individu', (string) $id);
        $fileService->init('gallery', (string) $id);
        $fileService->init('file', (string) $id);

        $form = $this->createForm(IndividuType::class, $individu, ['isNew' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addflash('success', 'Individu modifié.');
            return $this->redirectToRoute('app_arbre');
        }

        if ($form->isSubmitted()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addflash('error', $error->getMessage());
            }
        }

        $mariages = $individu->getTousLesMariages();
        $enfants = $individu->getTousLesEnfants();

        return $this->render('individu/edit.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Modifier : ' . $individu->getNomComplet(),
            'routecancel' => 'app_individu_list',
            'routedelete' => 'app_admin_individu_supprimer',
            'mode' => 'modifier',
            'form' => $form,
            'individu' => $individu,
            'mariages' => $mariages,
            'enfants' => $enfants,
        ]);
    }

    #[Route('/admin/individu/supprimer/{id}', name: 'app_admin_individu_supprimer')]
    public function supprimer(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('INDIVIDU_DELETE');

        $individu = $em->getRepository(Individu::class)->find($id);
        if (!$individu) {
            $this->addflash('error', 'Individu non trouvé.');

            return $this->redirectToRoute('app_individu_list');
        }

        try {
            $em->remove($individu);
            $em->flush();
            $this->addflash('success', 'Individu supprimé avec succès.');
        } catch (\Exception $e) {
            $this->addflash('error', 'Impossible de supprimer cet individu : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_individu_list');
    }

    #[Route('/admin/individu/rapide', name: 'app_admin_individu_rapide', methods: ['POST'])]
    public function rapide(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('INDIVIDU_CREATE');

        $data = json_decode($request->getContent(), true);

        $individu = new Individu();
        $individu->setNomNaissance($data['nomNaissance'] ?? '');
        $individu->setPrenom1($data['prenom1'] ?? null);
        $individu->setSexe($data['sexe'] ?? 'M');

        $em->persist($individu);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'id' => $individu->getId(),
            'nomComplet' => $individu->getNomComplet(),
        ]);
    }

    #[Route('/admin/individu/{id}/inline-edit', name: 'app_admin_individu_inline_edit', methods: ['POST'])]
    public function inlineEdit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');

        $individu = $em->getRepository(Individu::class)->find($id);
        if (!$individu) {
            return new JsonResponse(['success' => false, 'error' => 'Individu non trouvé.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nomNaissance'])) {
            $individu->setNomNaissance($data['nomNaissance']);
        }
        if (isset($data['prenom1'])) {
            $individu->setPrenom1($data['prenom1'] ?: null);
        }
        if (isset($data['dateNaissance'])) {
            $individu->setDateNaissance($data['dateNaissance'] ? new \DateTime($data['dateNaissance']) : null);
        }
        if (isset($data['dateDeces'])) {
            $individu->setDateDeces($data['dateDeces'] ? new \DateTime($data['dateDeces']) : null);
        }

        $em->flush();

        return new JsonResponse([
            'success' => true,
            'nomComplet' => $individu->getNomComplet(),
        ]);
    }

    #[Route('/admin/individu/{id}/lier', name: 'app_admin_individu_lier', methods: ['POST'])]
    public function lier(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');

        $individu = $em->getRepository(Individu::class)->find($id);
        if (!$individu) {
            return new JsonResponse(['success' => false, 'error' => 'Individu non trouvé.'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $typeLien = $data['typeLien'] ?? '';
        $cibleId = $data['cibleId'] ?? 0;

        $cible = $em->getRepository(Individu::class)->find($cibleId);
        if (!$cible) {
            return new JsonResponse(['success' => false, 'error' => 'Individu cible non trouvé.'], 404);
        }

        switch ($typeLien) {
            case 'pere':
                $cible->setPere($individu);
                break;
            case 'fils':
                $individu->setPere($cible);
                break;
            case 'mere':
                $cible->setMere($individu);
                break;
            case 'fille':
                $individu->setMere($cible);
                break;
            case 'conjoint':
                $mariage = new Mariage();
                $mariage->setIndividu1($individu);
                $mariage->setIndividu2($cible);
                $em->persist($mariage);
                break;
            default:
                return new JsonResponse(['success' => false, 'error' => 'Type de lien inconnu.'], 400);
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/admin/individu/{id}/files/{domain}', name: 'app_admin_individu_files')]
    public function files(int $id, string $domain, FileService $fileService): JsonResponse
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');
        $fileService->init($domain, (string) $id);
        $files = $fileService->list($domain, (string) $id);

        $data = [];
        foreach ($files as $file) {
            if ($file['isDirectory']) continue;
            $data[] = [
                'name' => $file['name'],
                'url' => '/bninefiles/image/' . $domain . '/' . $id . '?path=' . $file['path'],
                'thumbnail' => '/bninefiles/thumbnail/' . $domain . '/' . $id . '?path=' . $file['path'],
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/admin/individu/{id}/detacher/{childId}', name: 'app_admin_individu_detacher')]
    public function detacher(int $id, int $childId, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('INDIVIDU_EDIT');

        $enfant = $em->getRepository(Individu::class)->find($childId);
        if (!$enfant) {
            $this->addflash('error', 'Enfant non trouvé.');
            return $this->redirectToRoute('app_admin_individu_modifier', ['id' => $id]);
        }

        if ($enfant->getPere() && $enfant->getPere()->getId() === $id) {
            $enfant->setPere(null);
        }
        if ($enfant->getMere() && $enfant->getMere()->getId() === $id) {
            $enfant->setMere(null);
        }

        $em->flush();
        $this->addflash('success', 'Lien supprimé.');

        return $this->redirectToRoute('app_admin_individu_modifier', ['id' => $id]);
    }

    #[Route('/individu/{id}', name: 'app_individu_show')]
    public function show(int $id, EntityManagerInterface $em, FileService $fileService): Response
    {
        $individu = $em->getRepository(Individu::class)->find($id);
        if (!$individu) {
            $this->addflash('error', 'Individu non trouvé.');

            return $this->redirectToRoute('app_home');
        }

        $fileService->init('individu', (string) $id);
        $fileService->init('gallery', (string) $id);
        $fileService->init('file', (string) $id);

        return $this->render('individu/show.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => $individu->getNomComplet(),
            'individu' => $individu,
        ]);
    }
}
