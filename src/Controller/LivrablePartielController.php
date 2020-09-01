<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Commentaire;
use App\Entity\CompetenceValide;
use App\Entity\FilDeDiscussion;
use App\Entity\Formateur;
use App\Repository\ApprenantRepository;
use App\Repository\BriefRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CompetenceValideRepository;
use App\Repository\LivrablePartielRepository;
use App\Repository\PromosRepository;
use App\Repository\ReferentielsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use  App\Entity\LivrablePartiel;

class LivrablePartielController extends AbstractController
{
    /**
     * @Route(
     *     path="/api/formateurs/promo/{id_promo}/referentiel/{id_ref}/competences",
     *     name="getAppByComp",
     *     methods="GET")
     */
    public function getApprenantCollection( SerializerInterface $serializer, CompetenceValideRepository $competenceValideRepository, $id_promo, $id_ref)
    {
        $competencesValides = $competenceValideRepository->findOneBy(
            [
                "promos" => $id_promo, "referenciel" => $id_ref
            ]
        );

        $apprenant= ["Apprenant"=>$competencesValides->getApprenant()];

        $apprenants = $serializer->serialize($apprenant, 'json', ["groups" => ["competences:read"]]);


        return new JsonResponse($apprenants, Response::HTTP_OK, [], true);
    }


    /**
     * @Route(
     *     name="get_deux_it",
     *     path="/api/apprenants/{id}/livrablepartiels/{id_d}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::putAppLiv",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_item_operation_name"="get_deux_it"
     *     }
     * )
     */

    public function putAppLiv(Request $request, EntityManagerInterface $manager, ApprenantRepository $apprenantRepository, LivrablePartielRepository $livrablePartielRepository, int $id, int $id_d)
    {

        $etatTab= json_decode($request->getContent(),true);

        $apprenant = $apprenantRepository->findOneBY(["id" => $id]);

        $livrapartiel = $livrablePartielRepository->findoneBY(["id" => $id_d]);

        if (!$apprenant) {
            return new JsonResponse("L'apprenant dont l'id=" . $id . "n'existe pas", Response::HTTP_CREATED, [], true);

        }
        if (!$livrapartiel) {
            return new JsonResponse("Le livrable dont l'id=" . $id_d . "n'existe pas", Response::HTTP_CREATED, [], true);

        }
        foreach ($apprenant->getApprenantLivrablePartiels() as $apl) {
          if ($apl->getLivrablePartiel()->getId()== $id_d){
            $apl->setEtat($etatTab['etat']);

          }

        }
        $manager->flush();
        return $this->json("Modification reussi");

    }

    /**
     * @Route(
     *     name="post_formateur",
     *     path="/api/formateurs/livrablepartiels/{id}/commentaires",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::postAjoutFilDu",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_item_operation_name"="post_formateur"
     *     }
     * )
     */

    public function  postAjoutFilDu(SerializerInterface $serializer,EntityManagerInterface $manager,Request $request,TokenStorageInterface $token ,LivrablePartielRepository $livrablePartielRepository, CommentaireRepository $commentaireRepository, int $id)
    {
        $discussion= json_decode($request->getContent(),true);

       if (!isset($discussion['commentaire'])){
           return new JsonResponse("Ajouter des commentaires au fil de discussion", Response::HTTP_BAD_REQUEST, [], true);
       }
        $livpar = $livrablePartielRepository->findOneBY(["id"=>$id]);

        $user=$token->getToken()->getUser();

        if (!$livpar) {
            return new JsonResponse("Le livrable partiel dont l'id=" . $id . "n'existe pas", Response::HTTP_BAD_REQUEST, [], true);

        }

             if ($user instanceof Formateur || $user instanceof Apprenant) {

                 $fil= new FilDeDiscussion();
                 if (isset($discussion['titreFil'])){

                   $fil->setTitre($discussion['titreFil']);
                    $fil->setDate(new  \DateTime());

                 }

                 foreach ($discussion['commentaire'] as $commentaire){

                  $com= new Commentaire();
               $com->setDescription($commentaire['description'])
                   ->setCreatAt(new \DateTime())
                   ->setFilDeDiscussion($fil);
               if ($user instanceof Formateur){
                   $com->setFormateurs($user);
               }
                    $manager->persist($com);
                 }
                 $manager->persist($fil);

            }

        $manager->flush();
        return new JsonResponse("Fil de discussion et commentaires ajoutés", Response::HTTP_BAD_REQUEST, [], true);
    }

    /**
     * @Route(
     * name="form_stat",
     * path="api/formateur/promo/{idp}/referentiels/{idr}/statistique/competence",
     * methods={"GET"},
     * defaults={
     * "_api_resource_class"=LivrablePartiel::class,
     * "_api_collection_operation_name"="form_stat",
     * "deserialize" = false
     * }
     * )
     */

    public function getCompetences(ReferentielsRepository $repo,SerializerInterface $serializer,PromosRepository $promosRepository, $idp,$idr)
    {

        $promo = $promosRepository->find($idp);

            foreach($promo->getReferentiel()->getGroupeCompetences() as $grpc){
                foreach($grpc->getCompetence() as $competence){
                    $nb1=0;$nb2=0;$nb3=0;
                    foreach($competence->getCompetenceValides() as $compValid){
                        if ($compValid->getNiveau1() == true){
                            $nb1 += 1;
                        }
                        if ($compValid->getNiveau2() == true){
                            $nb2 += 1;
                        }
                        if ($compValid->getNiveau3() == true){
                            $nb3 += 1;
                        }
                    }
                    $tab[] = ["competence"=>$competence,"niveau 1"=>$nb1.' apprenant valide',"niveau 2"=>$nb2.' apprenant valide',"niveau 3"=>$nb3.' apprenant valide'];

                }
                // dd($tab);

            }

        return $this->json($tab,200,[],["groups"=>"grp"]);
    }

    /**
     * @Route(
     *  path="/api/formateurs/livrablepartiels/{id}/commentaires",
     *   name="recuperer_les_commentaires", methods={"GET"}
     *  )
     */
    public function LivrableComm($id, LivrablePartielRepository $partielRepository)
    {
        $livrable = $partielRepository->findOneBy(
            [
                "id"=>$id
            ]
        );
        if (!$livrable){
            return new JsonResponse("Le livrable partiel dont l'id=" . $id . "n'existe pas", Response::HTTP_BAD_REQUEST, [], true);
        }
        $TabCommentaires=[];

    foreach ($livrable->getApprenantLivrablePartiels() as $partiel){

       foreach ($partiel->getFilDeDiscussion()->getCommentaires() as $commentaire){
         $TabCommentaires[]= $commentaire;

       }
    }

        return $this->json(["Commentaires"=>$TabCommentaires],200,[],["groups"=>"commentaires"]);
    }


    /**
     * @Route(
     *     name="get_apprenant_briefs_valides",
     *     path="/api/apprenants/{id}/promo/{idc}/referentiel/{ide}/statistiques/briefs",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\LivrablePartielController::getStatCompetences",
     *          "__api_resource_class"=LivrablePartiel::class,
     *          "__api_collection_operation_name"="get_apprenant_briefs_valides"
     *     }
     * )
     */
    public function getStatBrief(ApprenantRepository $apprenantRepository,PromosRepository $promoRepository, ReferentielsRepository $referentielRepository, BriefRepository $briefRepository, int $id, int $idc, int $ide)
    {

        $apprenant = $apprenantRepository->find($id);
        $apprenants = [];

        if (!empty($apprenant)) {

            foreach ($apprenant->getCompetenceValides() as $competencesvalides) {
                if ($competencesvalides->getPromos()->getId() == $idc) {

                        if ($competencesvalides->getReferenciel()->getId() == $ide) {
                            foreach ($apprenant->getLivrableAttenduApprenants() as $key => $value) {
                                foreach ($value->getLivrableattendu()->getBrief() as $briefs) {
                                    if ($briefs->getEtatBrouillonsAssigneValide() == "valide" ) {
                                        $apprenants[] = [
                                           "Apprenant" => $apprenantRepository->findOneBy(['id' => $apprenant->getId()])
                                        ];
                                    } else {
                                        return $this->json("Valider les données");
                                    }
                                }
                            }
                        }
                }
            }

        }
        return $this->json($apprenants, 200, [], ["groups" => ["competence_collection"]]);
    }

}