<?php

namespace App\DataFixtures;

use App\Entity\IntegrationLog;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Provenance;
use App\Entity\Cafe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Flux 1 : Un succès
        $flux1 = new IntegrationLog();
        $flux1->setTypeFlux('Import_Commandes_AS400');
        $flux1->setStatut('Succès');
        $flux1->setCreatedAt(new \DateTimeImmutable('-2 hours'));
        $flux1->setUpdatedAt(new \DateTimeImmutable('-2 hours'));

        // On dit à Doctrine de garder cet objet en mémoire
        $manager->persist($flux1);

        // Flux 2 : Une erreur critique
        $flux2 = new IntegrationLog();
        $flux2->setTypeFlux('Export_Expeditions_Copilote');
        $flux2->setStatut('Erreur');
        $flux2->setMessageErreur('Timeout réseau. Impossible de joindre le serveur EDI externe.');
        $flux2->setCreatedAt(new \DateTimeImmutable('-30 minutes'));
        $flux2->setUpdatedAt(new \DateTimeImmutable('-30 minutes'));
        $manager->persist($flux2);

        // Flux 3 : En attente
        $flux3 = new IntegrationLog();
        $flux3->setTypeFlux('Synchro_Stock_Web');
        $flux3->setStatut('En attente');
        $flux3->setCreatedAt(new \DateTimeImmutable('now'));
        $flux3->setUpdatedAt(new \DateTimeImmutable('now'));
        $manager->persist($flux3);

        // Client 1
        $client = new Client();
        $client->setNom('Sanka');
        $client->setmail('sanka@example.com');
        $client->setDateNaissance(new \DateTime('1990-01-01'));
        $manager->persist($client);

        $client2 = new Client();
        $client2->setNom('Gustave');
        $client2->setmail('gustave@example.com');
        $client2->setDateNaissance(new \DateTime('1995-05-15'));
        $manager->persist($client2);

        $client3 = new Client();
        $client3->setNom(' Monoco');
        $client3->setmail('monoco@example.com');
        $client3->setDateNaissance(new \DateTime('1985-12-20'));
        $manager->persist($client3);

        $commande1 = new Commande();
        $commande1->setProduits('PS5');
        $commande1->setMarque('Sony');
        $commande1->setQuantite(2);
        $manager->persist($commande1);

        $commande2 = new Commande();
        $commande2->setProduits('Xbox Series X');
        $commande2->setMarque('Microsoft');
        $commande2->setQuantite(1);
        $manager->persist($commande2);

        $commande3 = new Commande();
        $commande3->setProduits('Nintendo Switch');
        $commande3->setMarque('Nintendo');
        $commande3->setQuantite(1);
        $manager->persist($commande3);

        $bresil = new Provenance();
        $bresil->setNom('Minas Gerais');
        $bresil->setPays('Brésil');
        $manager->persist($bresil);

        $ethiopie = new Provenance();
        $ethiopie->setNom('Sidamo');
        $ethiopie->setPays('Éthiopie');
        $manager->persist($ethiopie);

        // Café 1 (Brésil)
        $cafe1 = new Cafe();
        $cafe1->setNom('Méo Gastronomique');
        $cafe1->setEan('3254560000010');
        $cafe1->setStock(100);
        $cafe1->setProvenance($bresil); // jointure
        $manager->persist($cafe1);

        // Café 2 (Brésil aussi)
        $cafe2 = new Cafe();
        $cafe2->setNom('Méo Sélection Arabica');
        $cafe2->setEan('3254560000020');
        $cafe2->setStock(10);
        $cafe2->setProvenance($bresil); // Même provenance
        $manager->persist($cafe2);

        // Café 3 (Éthiopie)
        $cafe3 = new Cafe();
        $cafe3->setNom('Méo Bio Éthiopie');
        $cafe3->setEan('3254560000030');
        $cafe3->setStock(500);
        $cafe3->setProvenance($ethiopie); // Autre provenance
        $manager->persist($cafe3);

        // Café 4 (Un orphelin ? Non, on a dit "not nullable", donc on doit mettre une provenance)
        // Mais pour tester un LEFT JOIN plus tard, tu aurais pu mettre nullable: true dans l'entité.

        // On exécute la requête d'insertion en base (le COMMIT SQL)
        $manager->flush();
    }
}
