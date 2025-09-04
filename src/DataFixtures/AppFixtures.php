<?php

namespace App\DataFixtures;

use App\Entity\Ferme;
use App\Entity\Betail;
use App\Entity\Batiment;
use App\Entity\Zone;
use App\Entity\ZoneFerme;
use App\Entity\Volaille;
use App\Entity\Stock;
use App\Entity\Transaction;
use App\Entity\Employe;
use App\Entity\ProductionOeuf;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des fermes
        $ferme1 = new Ferme();
        $ferme1->setNom('Ferme des Prés Verts')
            ->setLocalisation('123 Route Agricole, 80000 Amiens')
            ->setType('elevage_mixte')
            ->setSuperficie('150.5')
            ->setDescription('Ferme d\'élevage mixte avec bovins, ovins et volailles')
            ->setDateCreation(new \DateTime('2020-01-15'));
        $manager->persist($ferme1);

        $ferme2 = new Ferme();
        $ferme2->setNom('Élevage du Soleil')
            ->setLocalisation('456 Chemin Rural, 62000 Arras')
            ->setType('elevage_caprin')
            ->setSuperficie('87.2')
            ->setDescription('Élevage spécialisé en caprins laitiers bio')
            ->setDateCreation(new \DateTime('2019-06-01'));
        $manager->persist($ferme2);

        // Créer des employés
        $employe1 = new Employe();
        $employe1->setPrenom('Jean')
            ->setNom('Dupont')
            ->setRole('responsable')
            ->setTelephone('06.12.34.56.78')
            ->setEmail('jean.dupont@presverts.fr')
            ->setSalaireMensuel('2800')
            ->setStatut('actif')
            ->setFerme($ferme1)
            ->setDateEmbauche(new \DateTime('2020-03-01'))
            ->setDateCreation(new \DateTime());
        $manager->persist($employe1);

        $employe2 = new Employe();
        $employe2->setPrenom('Marie')
            ->setNom('Martin')
            ->setRole('veterinaire')
            ->setTelephone('06.87.65.43.21')
            ->setEmail('marie.martin@elevagesoleil.fr')
            ->setSalaireMensuel('3200')
            ->setStatut('actif')
            ->setFerme($ferme2)
            ->setDateEmbauche(new \DateTime('2019-09-15'))
            ->setDateCreation(new \DateTime());
        $manager->persist($employe2);

        // Créer des zones de ferme pour ferme 1
        $zoneFermeProduction1 = new ZoneFerme();
        $zoneFermeProduction1->setNom('Zone de Production Nord')
            ->setNumeroIdentification('ZF-001')
            ->setType('production')
            ->setDescription('Zone principale de production animale')
            ->setSuperficie('2500.5')
            ->setLocalisation('Nord de la propriété')
            ->setCaracteristiques('Terrain plat, accès facile, proximité eau')
            ->setAcces('Route principale, chemin goudronné')
            ->setFerme($ferme1);
        $manager->persist($zoneFermeProduction1);

        $zoneFermeStockage1 = new ZoneFerme();
        $zoneFermeStockage1->setNom('Zone de Stockage')
            ->setNumeroIdentification('ZF-002')
            ->setType('stockage')
            ->setDescription('Zone de stockage des aliments et matériel')
            ->setSuperficie('800.2')
            ->setLocalisation('Est de la propriété')
            ->setCaracteristiques('Terrain surélevé, bien drainé')
            ->setAcces('Accès camions, quai de déchargement')
            ->setFerme($ferme1);
        $manager->persist($zoneFermeStockage1);

        // Créer des bâtiments pour ferme 1
        $batiment1 = new Batiment();
        $batiment1->setNom('Étable Principale')
            ->setNumeroIdentification('BAT-001')
            ->setType('etable')
            ->setDescription('Étable principale pour bovins laitiers')
            ->setSuperficie('450.5')
            ->setCapaciteMaximale(50)
            ->setStatut('actif')
            ->setDateConstruction(new \DateTime('2018-06-15'))
            ->setEquipements('Système de traite, Ventilation automatique')
            ->setFerme($ferme1)
            ->setZoneFerme($zoneFermeProduction1);
        $manager->persist($batiment1);

        $batiment2 = new Batiment();
        $batiment2->setNom('Bergerie')
            ->setNumeroIdentification('BAT-002')
            ->setType('bergerie')
            ->setDescription('Bergerie pour ovins')
            ->setSuperficie('220.3')
            ->setCapaciteMaximale(80)
            ->setStatut('actif')
            ->setDateConstruction(new \DateTime('2019-03-10'))
            ->setEquipements('Abreuvoirs automatiques, Aire de stockage')
            ->setFerme($ferme1)
            ->setZoneFerme($zoneFermeProduction1);
        $manager->persist($batiment2);

        // Créer des zones pour bâtiment 1 (Étable)
        $zone1 = new Zone();
        $zone1->setNom('Stabulation libre A')
            ->setNumeroIdentification('ZONE-001')
            ->setType('stabulation')
            ->setDescription('Zone de stabulation libre pour vaches laitières')
            ->setSuperficie('180.2')
            ->setCapaciteMaximale(25)
            ->setStatut('disponible')
            ->setEquipements('Logettes, Racleur automatique')
            ->setConditions('Ventilation naturelle, Éclairage LED')
            ->setBatiment($batiment1);
        $manager->persist($zone1);

        $zone2 = new Zone();
        $zone2->setNom('Stabulation libre B')
            ->setNumeroIdentification('ZONE-002')
            ->setType('stabulation')
            ->setDescription('Zone de stabulation libre pour génisses')
            ->setSuperficie('160.8')
            ->setCapaciteMaximale(20)
            ->setStatut('disponible')
            ->setEquipements('Logettes, Aire d\'exercice')
            ->setBatiment($batiment1);
        $manager->persist($zone2);

        $zone3 = new Zone();
        $zone3->setNom('Maternité')
            ->setNumeroIdentification('ZONE-003')
            ->setType('maternite')
            ->setDescription('Zone de vêlage et maternité')
            ->setSuperficie('45.5')
            ->setCapaciteMaximale(5)
            ->setStatut('disponible')
            ->setEquipements('Boxes de vêlage, Caméras de surveillance')
            ->setBatiment($batiment1);
        $manager->persist($zone3);

        // Créer des zones pour bâtiment 2 (Bergerie)
        $zone4 = new Zone();
        $zone4->setNom('Parc Principal')
            ->setNumeroIdentification('ZONE-004')
            ->setType('parc')
            ->setDescription('Parc principal pour brebis')
            ->setSuperficie('150.0')
            ->setCapaciteMaximale(60)
            ->setStatut('disponible')
            ->setEquipements('Mangeoires, Abreuvoirs')
            ->setBatiment($batiment2);
        $manager->persist($zone4);

        $zone5 = new Zone();
        $zone5->setNom('Nursery Agneaux')
            ->setNumeroIdentification('ZONE-005')
            ->setType('nursery')
            ->setDescription('Zone pour jeunes agneaux')
            ->setSuperficie('35.8')
            ->setCapaciteMaximale(20)
            ->setStatut('disponible')
            ->setEquipements('Lampes chauffantes, Biberons automatiques')
            ->setBatiment($batiment2);
        $manager->persist($zone5);

        // Créer du bétail pour ferme 1
        $bovin1 = new Betail();
        $bovin1->setNumeroIdentification('BV001')
            ->setType('bovin')
            ->setSousType('vache')
            ->setSexe('femelle')
            ->setRace('Holstein')
            ->setDateEntree(new \DateTime('2019-05-15'))
            ->setPoidsEntree('650.5')
            ->setMode('reproduction')
            ->setAge(5)
            ->setFerme($ferme1)
            ->setZone($zone1);
        $manager->persist($bovin1);

        $bovin2 = new Betail();
        $bovin2->setNumeroIdentification('BV002')
            ->setType('bovin')
            ->setSousType('vache')
            ->setSexe('femelle')
            ->setRace('Montbéliarde')
            ->setDateEntree(new \DateTime('2020-02-12'))
            ->setPoidsEntree('580.3')
            ->setMode('reproduction')
            ->setAge(4)
            ->setFerme($ferme1)
            ->setZone($zone1);
        $manager->persist($bovin2);

        $ovin1 = new Betail();
        $ovin1->setNumeroIdentification('OV001')
            ->setType('ovin')
            ->setSousType('mouton')
            ->setSexe('femelle')
            ->setRace('Lacaune')
            ->setDateEntree(new \DateTime('2020-03-20'))
            ->setPoidsEntree('75.2')
            ->setMode('reproduction')
            ->setAge(3)
            ->setFerme($ferme1)
            ->setZone($zone4);
        $manager->persist($ovin1);

        $ovin2 = new Betail();
        $ovin2->setNumeroIdentification('OV002')
            ->setType('ovin')
            ->setSousType('mouton')
            ->setSexe('male')
            ->setRace('Lacaune')
            ->setDateEntree(new \DateTime('2021-01-08'))
            ->setPoidsEntree('95.8')
            ->setMode('reproduction')
            ->setAge(2)
            ->setFerme($ferme1)
            ->setZone($zone4);
        $manager->persist($ovin2);

        // Créer des volailles pour ferme 1
        $volaille1 = new Volaille();
        $volaille1->setType('poule_pondeuse')
            ->setRace('Sussex')
            ->setEffectifInitial(250)
            ->setEffectif(245)
            ->setNumeroLot('PP2023-001')
            ->setAgeEntree(20)
            ->setMode('ponte')
            ->setFerme($ferme1)
            ->setDateEntree(new \DateTime('2023-01-15'))
            ->setDateCreation(new \DateTime());
        $manager->persist($volaille1);

        $volaille2 = new Volaille();
        $volaille2->setType('poule_chair')
            ->setRace('Ross 308')
            ->setEffectifInitial(500)
            ->setEffectif(494)
            ->setNumeroLot('PC2023-002')
            ->setAgeEntree(1)
            ->setMode('chair')
            ->setFerme($ferme1)
            ->setDateEntree(new \DateTime('2023-08-01'))
            ->setDateCreation(new \DateTime());
        $manager->persist($volaille2);

        // Créer des stocks
        $stock1 = new Stock();
        $stock1->setNom('Aliment Volaille Ponte')
            ->setType('provende')
            ->setQuantiteActuelle('2500')
            ->setQuantiteMinimum('500')
            ->setUnite('kg')
            ->setPrixUnitaireMoyen('0.85')
            ->setFerme($ferme1);
        $manager->persist($stock1);

        $stock2 = new Stock();
        $stock2->setNom('Foin Premium')
            ->setType('foin')
            ->setQuantiteActuelle('150')
            ->setQuantiteMinimum('25')
            ->setUnite('botte')
            ->setPrixUnitaireMoyen('12.50')
            ->setFerme($ferme1);
        $manager->persist($stock2);

        // Créer des transactions
        $transaction1 = new Transaction();
        $transaction1->setType('recette')
            ->setCategorie('vente_oeufs')
            ->setMontant('1250.80')
            ->setDescription('Vente œufs semaine 35')
            ->setDateTransaction(new \DateTime('2023-08-28'))
            ->setStatut('validee')
            ->setDateCreation(new \DateTime())
            ->setFerme($ferme1);
        $manager->persist($transaction1);

        $transaction2 = new Transaction();
        $transaction2->setType('depense')
            ->setCategorie('achat_aliment')
            ->setMontant('875.00')
            ->setDescription('Achat aliment volaille - 1 tonne')
            ->setDateTransaction(new \DateTime('2023-08-25'))
            ->setStatut('validee')
            ->setDateCreation(new \DateTime())
            ->setFerme($ferme1);
        $manager->persist($transaction2);

        $transaction3 = new Transaction();
        $transaction3->setType('recette')
            ->setCategorie('vente_animal')
            ->setMontant('2150.00')
            ->setDescription('Vente 3 bovins réforme')
            ->setDateTransaction(new \DateTime('2023-08-20'))
            ->setStatut('validee')
            ->setDateCreation(new \DateTime())
            ->setFerme($ferme1);
        $manager->persist($transaction3);

        $transaction4 = new Transaction();
        $transaction4->setType('depense')
            ->setCategorie('veterinaire')
            ->setMontant('320.50')
            ->setDescription('Visite vétérinaire - vaccinations')
            ->setDateTransaction(new \DateTime('2023-08-22'))
            ->setStatut('validee')
            ->setDateCreation(new \DateTime())
            ->setFerme($ferme1);
        $manager->persist($transaction4);

        // Créer production d'œufs
        $production1 = new ProductionOeuf();
        $production1->setDate(new \DateTime('2023-08-28'))
            ->setNombreOeufs(180)
            ->setEffectifLors(245)
            ->setDateCreation(new \DateTime())
            ->setVolaille($volaille1);
        $manager->persist($production1);

        $production2 = new ProductionOeuf();
        $production2->setDate(new \DateTime('2023-08-27'))
            ->setNombreOeufs(175)
            ->setEffectifLors(245)
            ->setDateCreation(new \DateTime())
            ->setVolaille($volaille1);
        $manager->persist($production2);

        $production3 = new ProductionOeuf();
        $production3->setDate(new \DateTime('2023-08-26'))
            ->setNombreOeufs(182)
            ->setEffectifLors(245)
            ->setDateCreation(new \DateTime())
            ->setVolaille($volaille1);
        $manager->persist($production3);

        // Créer des zones de ferme pour ferme 2
        $zoneFermeProduction2 = new ZoneFerme();
        $zoneFermeProduction2->setNom('Zone Bio Sud')
            ->setNumeroIdentification('ZF-003')
            ->setType('production')
            ->setDescription('Zone certifiée agriculture biologique')
            ->setSuperficie('1200.8')
            ->setLocalisation('Sud de la propriété')
            ->setCaracteristiques('Terrain en pente douce, exposition sud, parcours naturels')
            ->setAcces('Chemin rural, accès piétons')
            ->setFerme($ferme2);
        $manager->persist($zoneFermeProduction2);

        // Créer des bâtiments et zones pour ferme 2
        $batiment3 = new Batiment();
        $batiment3->setNom('Chèvrerie Bio')
            ->setNumeroIdentification('BAT-003')
            ->setType('chevrerie')
            ->setDescription('Chèvrerie certifiée agriculture biologique')
            ->setSuperficie('180.7')
            ->setCapaciteMaximale(40)
            ->setStatut('actif')
            ->setDateConstruction(new \DateTime('2019-04-22'))
            ->setEquipements('Salle de traite, Aire de couchage paillée')
            ->setFerme($ferme2)
            ->setZoneFerme($zoneFermeProduction2);
        $manager->persist($batiment3);

        $zone6 = new Zone();
        $zone6->setNom('Loge Chèvres Laitières')
            ->setNumeroIdentification('ZONE-006')
            ->setType('stabulation')
            ->setDescription('Zone principale pour chèvres laitières bio')
            ->setSuperficie('120.5')
            ->setCapaciteMaximale(30)
            ->setStatut('disponible')
            ->setEquipements('Cornadis, Aire paillée, Sortie extérieure')
            ->setBatiment($batiment3);
        $manager->persist($zone6);

        $zone7 = new Zone();
        $zone7->setNom('Nursery Chevreaux')
            ->setNumeroIdentification('ZONE-007')
            ->setType('nursery')
            ->setDescription('Zone d\'élevage des chevreaux')
            ->setSuperficie('25.2')
            ->setCapaciteMaximale(15)
            ->setStatut('disponible')
            ->setEquipements('Cases individuelles, Lampes IR')
            ->setBatiment($batiment3);
        $manager->persist($zone7);

        // Créer du bétail pour ferme 2
        $caprin1 = new Betail();
        $caprin1->setNumeroIdentification('CP001')
            ->setType('caprin')
            ->setSousType('chevre')
            ->setSexe('femelle')
            ->setRace('Alpine')
            ->setDateEntree(new \DateTime('2020-04-10'))
            ->setPoidsEntree('55.8')
            ->setMode('reproduction')
            ->setAge(4)
            ->setFerme($ferme2)
            ->setZone($zone6);
        $manager->persist($caprin1);

        $caprin2 = new Betail();
        $caprin2->setNumeroIdentification('CP002')
            ->setType('caprin')
            ->setSousType('chevre')
            ->setSexe('femelle')
            ->setRace('Saanen')
            ->setDateEntree(new \DateTime('2019-08-15'))
            ->setPoidsEntree('62.3')
            ->setMode('reproduction')
            ->setAge(5)
            ->setFerme($ferme2)
            ->setZone($zone6);
        $manager->persist($caprin2);

        $caprin3 = new Betail();
        $caprin3->setNumeroIdentification('CP003')
            ->setType('caprin')
            ->setSousType('bouc')
            ->setSexe('male')
            ->setRace('Alpine')
            ->setDateEntree(new \DateTime('2021-03-05'))
            ->setPoidsEntree('78.5')
            ->setMode('reproduction')
            ->setAge(3)
            ->setFerme($ferme2)
            ->setZone($zone6);
        $manager->persist($caprin3);

        $transaction5 = new Transaction();
        $transaction5->setType('recette')
            ->setCategorie('vente_produits')
            ->setMontant('890.00')
            ->setDescription('Vente lait de chèvre bio')
            ->setDateTransaction(new \DateTime('2023-08-28'))
            ->setStatut('validee')
            ->setDateCreation(new \DateTime())
            ->setFerme($ferme2);
        $manager->persist($transaction5);

        $manager->flush();
    }
}
