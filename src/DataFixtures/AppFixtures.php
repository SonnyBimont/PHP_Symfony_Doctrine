<?php

namespace App\DataFixtures;

use App\Entity\IntegrationLog;
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

        // On exécute la requête d'insertion en base (le COMMIT SQL)
        $manager->flush();
    }
}
