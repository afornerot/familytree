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
                        if ($childId !== $id && !in_array($childId, $childrenOf[$id])) {
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

    #[Route('/individu/{id}/family-data', name: 'app_individu_family_data')]
    public function individuData(int $id, EntityManagerInterface $em): JsonResponse
    {
        $individu = $em->getRepository(Individu::class)->find($id);
        if (!$individu) {
            return new JsonResponse(['error' => 'Individu non trouvé.'], 404);
        }

        $allIds = [$individu];

        if ($individu->getPere()) $allIds[] = $individu->getPere();
        if ($individu->getMere()) $allIds[] = $individu->getMere();

        foreach ($individu->getTousLesMariages() as $m) {
            $allIds[] = $m->getIndividu1();
            $allIds[] = $m->getIndividu2();
        }

        foreach ($individu->getTousLesEnfants() as $e) {
            $allIds[] = $e;
        }

        $unique = [];
        foreach ($allIds as $i) {
            $unique[$i->getId()] = $i;
        }

        $spousesOf = [];
        $childrenOf = [];
        $parentsOf = [];

        foreach ($individu->getTousLesMariages() as $m) {
            $id1 = (string) $m->getIndividu1()->getId();
            $id2 = (string) $m->getIndividu2()->getId();
            $spousesOf[$id1][] = $id2;
            $spousesOf[$id2][] = $id1;
        }

        foreach ($unique as $i) {
            $iid = (string) $i->getId();
            if ($i->getPere() && isset($unique[$i->getPere()->getId()])) {
                $pid = (string) $i->getPere()->getId();
                $parentsOf[$iid][] = $pid;
                $childrenOf[$pid][] = $iid;
            }
            if ($i->getMere() && isset($unique[$i->getMere()->getId()])) {
                $mid = (string) $i->getMere()->getId();
                $parentsOf[$iid][] = $mid;
                $childrenOf[$mid][] = $iid;
            }
        }

        foreach ($spousesOf as $sid => $spouses) {
            if (isset($unique[$sid]) && empty($childrenOf[$sid])) {
                $childrenOf[$sid] = [];
            }
            foreach ($spouses as $s) {
                if (!empty($childrenOf[$s])) {
                    foreach ($childrenOf[$s] as $childId) {
                        if ($childId !== $sid && (!isset($childrenOf[$sid]) || !in_array($childId, $childrenOf[$sid]))) {
                            $childrenOf[$sid][] = $childId;
                        }
                    }
                }
            }
        }

        $nodes = [];
        foreach ($unique as $i) {
            $iid = (string) $i->getId();
            $nodes[] = [
                'id' => $iid,
                'data' => [
                    'gender' => $i->getSexe() ?: 'M',
                    'first name' => trim(implode(' ', array_filter([
                        $i->getPrenom1(),
                        $i->getPrenom2(),
                        $i->getPrenom3(),
                    ]))),
                    'last name' => $i->getNomNaissance() ?: '',
                    'nom complet' => $i->getNomComplet(),
                    'birthday' => $i->getDateNaissance() ? $i->getDateNaissance()->format('d/m/Y') : '',
                    'death' => $i->getDateDeces() ? $i->getDateDeces()->format('d/m/Y') : '',
                    'lieu naissance' => $i->getLieuNaissance() ?: '',
                    'avatar' => $i->getPhoto() ?: '',
                ],
                'rels' => [
                    'parents' => $parentsOf[$iid] ?? [],
                    'spouses' => $spousesOf[$iid] ?? [],
                    'children' => $childrenOf[$iid] ?? [],
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
