<?php

namespace App\Controller;

use App\Entity\ProfilSortie;
use App\Repository\PromosRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilSortieController extends AbstractController
{
    /**
     * @Route(
     * name="apprenant_by_promoPS",
     * path="/api/admin/promo/{id}/profilsorties",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\ProfilSortie::getApprenantByPromoPS",
     * "_api_resource_class"=ProfilSortie::class,
     * "_api_collection_operation_name"="get_apprenant_by_promoPS"
     * }
     * )
     */
    public function getApprenantByPromoPS(PromosRepository $promosRepository, int $id )
    {
        $promo = $promosRepository->find($id);
            if (!$promo)
                return $this->json("le promo  avec id $id n'existe pas", Response::HTTP_BAD_REQUEST);

            $groupes = $promo->getGroupe();
            //dd($groupe);
            $grpApprenant = [];
            foreach ($groupes as $groupe) {
                foreach ($groupe->getApprenant() as $apprenant) {
                    if ($apprenant->getProfilSortie()) {
                        $grpApprenant[] = $apprenant->getProfilSortie();
                    }
                }
            }
            return $this->json($grpApprenant, Response::HTTP_OK);

    }


    /**
     * @Route(
     * name="post_profilSorties",
     * path="/api/admin/profilSorties",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\ProfilSortie::addProfilSortie",
     * "_api_resource_class"=ProfilSortie::class,
     * "_api_collection_operation_name"="post_profilSorties"
     * }
     * )
     */
    public function addProfilSortie(SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
        $jsonGroupC= $request->getContent();


        $groupCObject= $serializer->deserialize($jsonGroupC,ProfilSortie::class,'json');

        $manager->persist($groupCObject);
        $manager->flush();
        return new JsonResponse('success', Response::HTTP_CREATED,[],'true');
    }


      /**
     * @Route(
       *  name="get_apprenat_profil",
       * path="api/admin/promo/{id}/profilsortie/{id1}",
       * methods={"GET"},
       * defaults={
       * "_controller"="\app\Controller\ProfilSortie::getprofilSortiesById",
       * "_api_resource_class"=ProfilSortie::class,
       * "_api_collection_operation_name"="get_profilSortie"
       * }
       * )
     */
    public function getprofilSortiesById(ProfilSortieRepository $profilSortieRepository,PromosRepository $promosRepository,$id,$id1)
    {
        //recuperation promo par id
        $promo = $promosRepository->find($id);
        //recuperation d'un profil de sorti par id
        $profileSorti = $profilSortieRepository->find($id1);
        // on verifie si le promo ou le profil de sortie est null
        if (!$promo || !$profileSorti) {
            return $this->json("l'id du promo ou du profilSortie  n'existe pas", Response::HTTP_BAD_REQUEST);
        }
        else{
            $gprApprenant = [];
            foreach ($promo->getGroupe() as $groupe) {
                foreach ($groupe->getApprenant() as $apprenant) {
                   foreach ($apprenant->getProfilSortie() as $ps) {
                        if($ps->getId() == $id1){
                            $gprApprenant =$profileSorti;
                        }
                   }
                }
            }

        }
      
        return $this->json($gprApprenant, Response::HTTP_OK,[],["groups"=>"profilSAppS:read"]);

    }
}
