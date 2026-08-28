<?php

namespace App\Controller;

use App\Entity\Individu;
use App\Repository\IndividuRepository;
use App\Repository\MariageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArbreController extends AbstractController
{
    #[Route('/arbre', name: 'app_arbre')]
    public function index(IndividuRepository $individuRepository): Response
    {
        $individus = $individuRepository->findBy([], ['nomNaissance' => 'ASC']);
        $selectData = array_map(fn($i) => ['id' => $i->getId(), 'text' => $i->getNomComplet()], $individus);

        return $this->render('arbre/tree.html.twig', [
            'usemenu' => true,
            'usesidebar' => false,
            'title' => 'Arbre Généalogique',
            'individus' => $selectData,
        ]);
    }

    #[Route('/arbre/data', name: 'app_arbre_data')]
    public function data(IndividuRepository $individuRepository, MariageRepository $mariageRepository): JsonResponse
    {
        $individus = $individuRepository->findBy([], ['nomNaissance' => 'ASC']);
        $mariages = $mariageRepository->findAll();

        $spousesOf = [];
        $childrenOf = [];
        $parentsOf = [];

        foreach ($mariages as $mariage) {
            $id1 = (string) $mariage->getIndividu1()->getId();
            $id2 = (string) $mariage->getIndividu2()->getId();
            $spousesOf[$id1][] = $id2;
            $spousesOf[$id2][] = $id1;
        }

        foreach ($individus as $individu) {
            $id = (string) $individu->getId();
            if ($individu->getPere()) {
                $pid = (string) $individu->getPere()->getId();
                $parentsOf[$id][] = $pid;
                $childrenOf[$pid][] = $id;
            }
            if ($individu->getMere()) {
                $mid = (string) $individu->getMere()->getId();
                $parentsOf[$id][] = $mid;
                $childrenOf[$mid][] = $id;
            }
        }

        foreach ($spousesOf as $id => $spouses) {
            if (empty($childrenOf[$id])) {
                $childrenOf[$id] = [];
            }
            foreach ($spouses as $sid) {
                if (!empty($childrenOf[$sid])) {
                    foreach ($childrenOf[$sid] as $childId) {
                        if (!in_array($childId, $childrenOf[$id])) {
                            $childrenOf[$id][] = $childId;
                        }
                    }
                }
            }
        }

        $nodes = [];
        foreach ($individus as $individu) {
            $id = (string) $individu->getId();

            $nodes[] = [
                'id' => $id,
                'data' => [
                    'gender' => $individu->getSexe() ?: 'M',
                    'first name' => trim(implode(' ', array_filter([
                        $individu->getPrenom1(),
                        $individu->getPrenom2(),
                        $individu->getPrenom3(),
                    ]))),
                    'last name' => $individu->getNomNaissance() ?: '',
                    'nom complet' => $individu->getNomComplet(),
                    'birthday' => $individu->getDateNaissance() ? $individu->getDateNaissance()->format('d/m/Y') : '',
                    'death' => $individu->getDateDeces() ? $individu->getDateDeces()->format('d/m/Y') : '',
                    'lieu naissance' => $individu->getLieuNaissance() ?: '',
                    'avatar' => $individu->getPhoto() ?: '',
                ],
                'rels' => [
                    'parents' => $parentsOf[$id] ?? [],
                    'spouses' => $spousesOf[$id] ?? [],
                    'children' => $childrenOf[$id] ?? [],
                ],
            ];
        }

        return new JsonResponse($nodes);
    }

    #[Route('/arbre/orphelins', name: 'app_arbre_orphelins')]
    public function orphelins(IndividuRepository $individuRepository): JsonResponse
    {
        $orphelins = $individuRepository->findOrphelins();

        $data = [];
        foreach ($orphelins as $individu) {
            $data[] = [
                'id' => $individu->getId(),
                'nomComplet' => $individu->getNomComplet(),
                'sexe' => $individu->getSexe(),
                'dateNaissance' => $individu->getDateNaissance() ? $individu->getDateNaissance()->format('Y') : null,
                'photo' => $individu->getPhoto(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/arbre/search', name: 'app_arbre_search')]
    public function search(\Symfony\Component\HttpFoundation\Request $request, IndividuRepository $individuRepository): JsonResponse
    {
        $query = $request->query->get('q', '');

        if (strlen($query) < 2) {
            return new JsonResponse([]);
        }

        $individus = $individuRepository->searchByName($query);

        $data = [];
        foreach ($individus as $individu) {
            $data[] = [
                'id' => $individu->getId(),
                'nomComplet' => $individu->getNomComplet(),
                'sexe' => $individu->getSexe(),
            ];
        }

        return new JsonResponse($data);
    }
}
