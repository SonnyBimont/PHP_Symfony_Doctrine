<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

final class HelloController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function index(ClientRepository $client): Response
    {
$clientList = $client->findAll();
        return $this->render('hello/index.html.twig', [
            'controller_name' => 'HelloController',
            'clients' => $clientList,
        ]);
    }

    #[Route (path:'/hello/{id}', name: 'app_hello_show')]
    public function show(int $id, ClientRepository $client, CommandeRepository $commande): Response
    {
        // 1. On cherche le client
        $client = $client->find($id);
        // 2. On récupère les commandes
        $commandeList = $commande->findAll();

        // 3. SÉCURITÉ : Si le client n'existe pas, on déclenche une page 404 officielle
        if (!$client) {
            throw $this->createNotFoundException('Le client numéro ' . $id . ' n\'existe pas dans la base.');
        }

        return $this->render('hello/show.html.twig', [
            'client' => $client,
            'commandes' => $commandeList,
        ]);
    }
}
