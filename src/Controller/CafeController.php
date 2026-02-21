<?php

namespace App\Controller;

use App\Repository\CafeRepository;
use App\Repository\ProvenanceRepository;
use App\Form\CafeStockType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormTypeInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CafeController extends AbstractController
{
    #[Route('/cafe', name: 'app_cafe')]
    public function index(ProvenanceRepository $provenance, CafeRepository $cafe): Response
    {
$provenanceList = $provenance->findAll();
$cafeList = $cafe->findAll();
        return $this->render('cafe/index.html.twig', [
            'title' => 'Test cafe provenance jointure',
            'provenances' => $provenanceList,
            'cafes' => $cafeList
        ]);
    }

    #[Route(path: '/cafe/{id}', name: 'app_cafe_show')]
    public function show(int $id, ProvenanceRepository $provenance, CafeRepository $cafe): Response
    {
        // 1. On cherche le café
        $cafe = $cafe->find($id);

        // Si le café n'existe pas : messaage 404
        if (!$cafe) {
            throw $this->createNotFoundException('Le café numéro ' . $id . ' n\'existe pas dans la base.');
        }

        return $this->render('cafe/show.html.twig', [
            'cafe' => $cafe,
        ]);
    }

     #[Route(path: 'cafe/{id}/edit', name: 'app_cafe_edit')]
     public function edit(int $id, CafeRepository $cafe, Request $request, EntityManagerInterface $em): Response
     {
        // 1. On récupère le café à modifier
        $cafe = $cafe->find($id);
        if (!$cafe) {
            throw $this->createNotFoundException('Le café numéro ' . $id . ' n\'existe pas dans la base.');
        }

        // 2. On crée le formulaire
        $form = $this->createForm(CafeStockType::class, $cafe);

        // 3. ON GÈRE LA REQUÊTE (Le moment clé)
        $form->handleRequest($request);

        // 4. Est-ce que le formulaire a été soumis et est-il valide ?
        if ($form->isSubmitted() && $form->isValid()) {

        // 5. On enregistre (UPDATE SQL automatique)
            $em->flush();

         // 6. Feedback utilisateur (Message Flash)
            $this->addFlash('success', 'Le stock a été mis à jour !');

        // 7. Redirection (pour éviter de re-soumettre si on fait F5)
            return $this->redirectToRoute('app_cafe_show', ['id' => $cafe->getId()]);
        }

        return $this->render('cafe/edit.html.twig', [
            'cafe' => $cafe,
            'form' => $form
        ]);
     }
}
