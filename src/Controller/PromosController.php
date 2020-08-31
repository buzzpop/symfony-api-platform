<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Groupe;
use App\Repository\ApprenantRepository;
use App\Repository\PromosRepository;
use App\Repository\ReferentielsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Promos;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PromosController extends AbstractController
{
    private $serializer;
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer= $serializer;
    }

    /**
     * @Route(
     * name="post_promos",
     * path="/api/admin/promo",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\Promos::addPromos",
     * "_api_resource_class"=Promos::class,
     * "_api_collection_operation_name"="post_promos"
     * }
     * )
     */
    public function addPromo(\Swift_Mailer $swift_Mailer, ReferentielsRepository $referentielsRepository,Request $request, EntityManagerInterface $manager, ValidatorInterface $validator, ApprenantRepository $apprenantRepository)
    {
        $myMail="rasprod221@gmail.com";
        $promoJson= $request->getContent();

        $promoTab= $this->serializer->decode($promoJson,'json');


        if (!isset($promoTab['referentiel'])){
            return new JsonResponse('Affecter au moins un referentiel au promos', Response::HTTP_BAD_REQUEST,[],'true');
        }

        $postReferentiel= explode("/",$promoTab['referentiel']);

        $refId=(int)$postReferentiel[count($postReferentiel)-1];

        $findRef= $referentielsRepository->findOneBy(
            [
                "id"=>$refId
            ]
        );


       if (!$findRef){
           return new JsonResponse('le referentiel ajouter n\'existe pas', Response::HTTP_BAD_REQUEST,[],'true');
       }
        $promoObj = $this->serializer->denormalize($promoTab,Promos::class,'json');

       $promoObj->setReferentiel($findRef);

        if (!isset($promoTab['groupe'])){
            return new JsonResponse('Vous devez obligatoirement ajouter un groupe principal', Response::HTTP_BAD_REQUEST,[],'true');
        }

        foreach ($promoTab['groupe'] as $group)
        {

          $apprenants= isset($group['apprenant']) ? $group['apprenant']: [];

           if (!count($apprenants)){
               return new JsonResponse( 'Ajouter des Apprenant ("par emails") dans le groupe principal' ,Response::HTTP_BAD_REQUEST,[],true);
           }
            $groupObj= $this->serializer->denormalize($group,"App\Entity\Groupe");


            $groupObj->setNom($group['nom'])
                ->setStatut($group['statut'])
                ->setType($group['type']);
            if (!isset($group['dateCreation'])){
                $groupObj->setDateCreation(new \DateTime());
            }else{
                $groupObj->setDateCreation($group['dateCreation']);
            }
           foreach ($apprenants as $apprenant)
           {

               $apprenantMail= $apprenantRepository->findOneBy(
                   [
                       "email"=>$apprenant['email']
                   ]
               );
              if (!$apprenantMail){
                  return new JsonResponse( 'l\'Apprenant dont l\'email est '.$apprenant['email'].' n\'existe pas' ,Response::HTTP_BAD_REQUEST,[],true);
              }
                $app= new Apprenant();
                $a= new Apprenant();

              $app->setEmail($apprenantMail->getEmail())
                  ->setPassword('passer')
                  ->setFirstName($apprenantMail->getFirstName())
                  ->setLastName($apprenantMail->getLastName())
                  ->setProfil($apprenantMail->getProfil());

               $groupObj->addApprenant($a);
               dd($groupObj);


              $mail= (new \Swift_Message("Sélection Sonatel Academy"));
              $mail->setFrom($myMail)
                  ->setTo($apprenantMail->getEmail())
                  ->setSubject("SONATEL ACADEMY RESULTATS SELECTION")
                  ->setBody("Bonjour Cher(e) ".$apprenantMail->getFirstName()." ".$apprenantMail->getLastName()."
                  Félicitations!!! vous avez été sélectionné(e) suite à votre test dentré à la Sonatel Academy.
                  Veuillez utiliser ces informations pour vous connecter à votre promos. Username: ".$apprenantMail->getEmail().
                  " Password: password. A bientot")
                  ;

              $swift_Mailer->send($mail);

           }
            $manager->persist($groupObj);
            $promoObj->addGroupe($groupObj);


        }


        $manager->persist($promoObj);
        $manager->flush();
        return new JsonResponse('promo créé', Response::HTTP_CREATED,[],'true');
    }


}
