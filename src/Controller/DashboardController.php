<?php

namespace App\Controller;

// On importe notre Magasinier (Repository)
use App\Repository\IntegrationLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    // C'est la "Route" : l'URL qui déclenche cette fonction
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(IntegrationLogRepository $repository): Response
    {
    // 1. On demande au magasinier d'aller chercher tous les flux.
    // On les trie par date de création décroissante (le plus récent en haut).
        $fluxList = $repository->findBy([], ['createdAt' => 'DESC']);
//dd($fluxList);

    // 2. On envoie ces données à la Vue (notre fichier HTML Twig).
    // C'est EXACTEMENT comme passer des "props" à un composant React.
        return $this->render('dashboard/index.html.twig', [
            'titre_page' => 'Supervision des Flux ERP',
            'flux_data' => $fluxList, // On donne le tableau de résultats à Twig
        ]);
    }

    #[Route(path:'/dashboard/traiter/{id}', name:'app_traiter_flux')]
    // Nouvelle route avec un paramètre variable {id}
    // C'est comme une API : /dashboard/traiter/1, /dashboard/traiter/2, etc.
    public function traiterErreur(Int $id, IntegrationLogRepository $repository, \Doctrine\ORM\EntityManagerInterface $manager): Response
    {
    // 1. On va chercher le flux précis grâce à son ID
$flux = $repository->find($id);

    // Sécurité : Si l'ID n'existe pas, on arrête tout
if (!$flux) {
    throw $this->createNotFoundException('Flux non trouvé');
}
    // 2. On modifie l'objet (Setter)
    // On change le statut et on vide le message d'erreur
$flux->setStatut('En cours de traitement');
$flux->setMessageErreur(null);

    // 3. On enregistre en base (Le fameux Flush)
    // Pas besoin de 'persist()' car l'objet existe déjà, Doctrine le surveille.
$manager->flush();

    // 4. On redirige l'utilisateur vers le tableau de bord
    // Pour lui, c'est instantané : il clique, la page se recharge, le statut a changé.
        return $this->redirectToRoute('app_dashboard');
    }
}
