<?php

namespace App\DataFixtures;

use App\Entity\Competences;
use App\Entity\GroupeCompetences;
use App\Entity\GroupeTag;
use App\Entity\Profil;
use App\Entity\Referentiels;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Apprenant;


use App\Entity\Chat;

use App\Entity\Formateur;
use App\Entity\Groupe;
use App\Entity\Niveau;
use App\Entity\Promos;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        $profilTab=['Administrateur','CM'];
        $tabDev=['html','php','javascript','mysql'];
        $tabGestion=['Anglais des affaires','Marketing','Community management'];
        $profil1 = new Profil();
                    $profil1->setLibelle("Apprenant");
                    $manager->persist($profil1);
        
                    $profil2 = new Profil();
                    $profil2->setLibelle("Formateur");
                    $manager->persist($profil2);
        for ($i=0; $i < 3 ; $i++) { 
            $groupe1 = new Groupe;
            $groupe1->setNom('Groupe'.($i+1))
                    ->setDateCreation(new \DateTime())
                    ->setStatut('Statut'.($i+1))
                    ->setType('Type'.($i+1))
                    ;
                    $manager->persist($groupe1);
                    for ($u=0; $u<4; $u++) {
                        $a = new Apprenant();
                        $hash = $this->encoder->encodePassword($a, 'password');
                        $a->setFirstName($faker->firstName())
                            ->setLastName($faker->lastName)
                            ->setEmail($faker->email)
                            ->setPassword($hash)
                            ->setProfil($profil1)
                            ->addGroupe($groupe1);
            
                        $manager->persist($a);
                    }

        }
        for ($p=0; $p<2; $p++){
            $profil= new Profil();
            $profil->setLibelle($profilTab[$p]);

            $manager->persist($profil);
            for ($u=0; $u<4; $u++) {
                $user = new User();
                $hash = $this->encoder->encodePassword($user, 'password');
                $user->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setPassword($hash)
                    ->setProfil($profil);

                $manager->persist($user);
            }
        }
        for ($u=0; $u<4; $u++) {
            $formateur = new Formateur();
            $hash = $this->encoder->encodePassword($formateur, 'password');
            $formateur->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash)
                ->setProfil($profil2)
                ->addGroupe($groupe1);
            $manager->persist($formateur);
        }



                $groupe= new GroupeCompetences();
                $groupe->setLibelle('Developpement Web')
                    ->setDescriptif('description developpement web');

                $manager->persist($groupe);
        for ($c=0; $c<count($tabDev); $c++){

            $comp= new Competences();
            $comp->setLibelle($tabDev[$c])
                ->addGroupeCompetence($groupe);

            $manager->persist($comp);

        }

                $groupe2= new GroupeCompetences();
                $groupe2->setLibelle('Gestion de Projet')
                    ->setDescriptif('description gestion de projet');
                $manager->persist($groupe2);
        for ($c=0; $c<count($tabGestion); $c++){

            $comp= new Competences();
            $comp->setLibelle($tabGestion[$c])
                ->addGroupeCompetence($groupe2);
            $manager->persist($comp);


        }

            $referentiel= new Referentiels();
            $referentiel
                ->setLibelle('Referentiel DevWeb')
                ->setPresentation('referentiel devWeb')
                ->setProgramme('algo, programmation web, mobile')
                ->setCritereAdmission('avoir moins de 35 ans')
                ->setCritereEvaluation('test logique, base de donnee, math, programmation')
                ->addGroupeCompetence($groupe)
                ;
            $manager->persist($referentiel);
         $referentiel1= new Referentiels();
            $referentiel1
                ->setLibelle('Referentiel Marketing Digital')
                ->setPresentation('referentiel marketing')
                ->setProgramme('anglais, marketing , community management')
                ->setCritereAdmission('avoir moins de 35 ans')
                ->setCritereEvaluation('test anglais, , francais')
                ->addGroupeCompetence($groupe2)
                ;
            $manager->persist($referentiel1);


            $promos = new Promos();
            $promos->setLangue('Français')
                    ->setTitre('Promos 3')
                    ->setDescription('Description Promos 3')
                    ->setLieu('Orange Digital Center')
                    ->setReferenceAgate('Developpement Web')
                    ->setDateDebut($faker->dateTime())
                    ->setDateFinProvisoire($faker->dateTime())
                    ->setFabrique('Projet fil Rouge')
                    ->setDateFinReelle($faker->dateTime())
                    ->setEtat('Actif')
                    ->setReferentiel($referentiel1)
                ->addFormateur($formateur)
                ->addGroupe($groupe1)
                    ;
                $manager->persist($promos);
        for ($u=0; $u<4; $u++) {
            $formateur = new Formateur();
            $hash = $this->encoder->encodePassword($formateur, 'password');
            $formateur->addPromo($promos)
            ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash)
                ->setProfil($profil2)
                ->addGroupe($groupe1)
                ;

        $gtag1= new GroupeTag();
        $gtag1
            ->setLibelle('taggroupe developpement');

        $tag1= new Tag();
        $tag1
            ->setLibelle('tag1')
            ->setDescriptif('description1')
            ->addGroupeTag($gtag1);
        $manager->persist($gtag1);
        $manager->persist($tag1);

        $gtag2= new GroupeTag();
        $gtag2
            ->setLibelle('taggroupe DATA ARTISANT');
        $tag2= new Tag();
        $tag2
            ->setLibelle('tag2')
            ->setDescriptif('description2')
            ->addGroupeTag($gtag2);
        $manager->persist($gtag2);
        $manager->persist($tag2);

        $niveau = new Niveau();
        $niveau->setLibelle('Niveau1')
                ->setCritereEvaluation('CritèreEvaluation')
                ->setGroupeAction('Groupe Action')
                ->setCompetences($comp)
                ;
            $manager->persist($niveau);



            $chat = new Chat();
            $chat->setMessage('Ceci est un commentaire')
                    ->setPieceJointes('piece Jointes')
                    ->setUser($user)
                    ->setPromos($promos)
                    ;
                $manager->persist($chat);
    


        // $product = new Product();
        // $manager->persist($product);

        for ($u=0; $u<4; $u++) {
            $a = new Apprenant();
            $hash = $this->encoder->encodePassword($a, 'password');
            $a->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash)
                ->setProfil($profil1);


            $manager->persist($a);
        }
        $manager->flush();
    }
}

}

