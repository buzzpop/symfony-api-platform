<?php

namespace App\Controller;

use App\Repository\BriefRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Brief;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\BriefMaPromosRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Apprenant;
use App\Entity\BriefApprenant;
use App\Entity\Formateur;

use App\Entity\Promos;
use App\Entity\Groupe;
use App\Entity\Ressource;
use App\Entity\Niveau;
use App\Entity\Tag;
use App\Entity\EtatBriefGroupe;
use App\Entity\LivrableAttenduApprenant;
use App\Entity\BriefMaPromos;
use App\Repository\BriefApprenantRepository;
use App\Repository\LivrableAttenduApprenantRepository;
use App\Repository\ApprenantRepository;
use App\Repository\ApprenantLivrablePartielRepository;
use App\Repository\LivrablePartielRepository;
use App\Repository\EtatBriefGroupeRepository;
use App\Repository\PromosRepository;


class BriefController extends AbstractController
{
    /**
     * @Route(
     * name="get_brief",
     * path="/api/formateurs/briefs",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Brief:getBrief",
     * "_api_resource_class"=Brief::class,
     * "_api_collection_operation_name"="get_brief"
     * }
     * )
     */
    public function getBrief(BriefRepository $briefRepository)
    {
        $brief = $briefRepository->findAll();
        foreach ($brief as $item) {

            $item->setImagePromos(utf8_encode(base64_decode(stream_get_contents($item->getImagePromos()))));
        }

        return $this->json($brief, Response::HTTP_OK);
    }


    /**
     * @Route(
     *  name="dupliquer_brief",
     *  path="/api/formateurs/brief/{id}",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::DuplBrief",
     *      
     *      "_api_collection_operation_name"="dupliquer_brief"
     *  }
     * )
     */
    public function DuplBrief(SerializerInterface $serializer, $id,Request $request, EntityManagerInterface $manager)
    {
        $entityManager = $this->getDoctrine()->getManager();;
        $Brief = $entityManager->getRepository(Brief::class)->find($id);
        $formateurs = $Brief->getFormateurs();
        $img = $Brief->getImagePromos();
        $briefTab=$serializer->decode($serializer->serialize($Brief,"json"),"json");
        $briefTab['id'] = null;
        $briefTab['formateurs'] = null;
        $brief = $serializer->denormalize($briefTab, Brief::class, true);
        $brief->setFormateurs($formateurs);
        $brief->setImagePromos($img);
        // dd($brief);
        $manager->persist($brief);
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);

    }

    /**
     * @Route(
     * name="get_brief_groupe_promo",
     * path="/api/formateurs/promo/{id_p}/groupe/{id_g}/briefs",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Brief:getBriefByGroupByPromo",
     * "_api_resource_class"=Brief::class,
     * "_api_collection_operation_name"="get_brief_groupe_promo"
     * }
     * )
     */
    public function getBriefByGroupByPromo(int $id_g, int $id_p,BriefRepository $briefRepository)
    {
        $briefs= $briefRepository->getBriefByGroupByPromo($id_g,$id_p);

        foreach ($briefs as $item) {

            $item->setImagePromos( utf8_encode(base64_decode(stream_get_contents($item->getImagePromos()))));
        }

        return $this->json($briefs,Response::HTTP_OK,[],["groups"=>"brief:read"]);

    }

    /**
     * @Route(
     * name="get_brief_promo",
     * path="/api/formateurs/promos/{id}/briefs",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Brief:getBriefByPromo",
     * "_api_resource_class"=Brief::class,
     * "_api_collection_operation_name"="get_brief_promo"
     * }
     * )
     */
    public function getBriefByPromo(int $id,BriefRepository $briefRepository)
    {
        $briefs = $briefRepository->getBriefPromo($id);

        foreach ($briefs as $item) {

            $item->setImagePromos(utf8_encode(base64_decode(stream_get_contents($item->getImagePromos()))));
        }

        return $this->json($briefs, Response::HTTP_OK, [], ["groups" => "brief:read"]);

    }
    /**
     *@Route(
     *  name="get_livrable_partiel_by_brief",
     *  path="/api/apprenants/{id_ap}/promo/{id_pr}/briefs/{id_br}",
     *  methods={"GET"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::getLivrablePartiel",
     *      "_api_resource_class"=Brief::class,
     *      "_api_collection_operation_name"="get_livrable_partiel_by_brief"
     *  }
     * )
     */
    public function getLivrablePartiel(BriefMaPromosRepository $rep, ApprenantLivrablePartielRepository $A_L_rep, LivrablePartielRepository $Lpa, $id_ap, $id_pr, $id_br)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Apprenant = $entityManager->getRepository(Apprenant::class)->find($id_ap);
        $Brief = $entityManager->getRepository(Brief::class)->find($id_br);
        $Promos = $entityManager->getRepository(Promos::class)->find($id_pr);
        if (!$Apprenant) {
            throw $this->createNotFoundException(
                'Pas d\'Apprenant Pour l\'id '.$id_ap
            );
        }
        if (!$Brief) {
            throw $this->createNotFoundException(
                'Pas de Brief Pour l\'id '.$id_br
            );
        }
        if (!$Promos) {
            throw $this->createNotFoundException(
                'Pas de Promos Pour l\'id '.$id_pr
            );
        }
        $brief = $rep->findBriefPromo($id_br, $id_pr)[0];
        $ap = $A_L_rep->findByIdAp($id_ap);
        foreach($ap as $appLP){
            $TabLPA = $Lpa->findByIdALP($appLP->getId(), $brief->getId());
            foreach($TabLPA as $l){
                $tabLP[] = $l;
            }
        }
        return $this->json($tabLP,Response::HTTP_OK);
    }
    /**
     * @Route(
     *  name="add_brief",
     *  path="/api/formateurs/brief",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::addBrief",
     *      
     *      "_api_collection_operation_name"="add_brief"
     *  }
     * )
     */
    public function addBrief(SerializerInterface $serializer,Request $request, EntityManagerInterface $manager, PromosRepository $promosRep)
    {
        $brief = $request->request->all();
        $img = $request->files->get("imagePromos");
        $img = fopen($img->getRealPath(), "rb");
        $tag = explode(",", $brief['Tag']);
        $formateurs = $brief['formateur'];
        $niveau = explode(",", $brief['niveaux']);
        $ressources = explode(",", $brief['ressource']);
        $entityManager = $this->getDoctrine()->getManager();
        $BriefObject = $serializer->denormalize($brief, Brief::class);
        $BriefObject->setImagePromos($img);

        foreach($tag as $key){
            $BriefObject->addTag($entityManager->getRepository(Tag::class)->find($key));
        }
        foreach($niveau as $key){
            $BriefObject->addNiveau($entityManager->getRepository(Niveau::class)->find($key));
            
        }
        foreach($ressources as $key){
            $BriefObject->addRessource($entityManager->getRepository(Ressource::class)->find($key));
        }
        $BriefObject->setFormateurs($entityManager->getRepository(Formateur::class)->find($formateurs));
        $manager->persist($BriefObject);
        if (isset($brief['groupe']) && !empty($brief['groupe'])){
            $groupe = explode(",", $brief['groupe']);
            foreach($groupe as $key){
                $grp = $entityManager->getRepository(Groupe::class)->find($key);
                $etatBrGr = new EtatBriefGroupe;
                $etatBrGr->setGroupe($grp);
                $etatBrGr->setBrief($BriefObject);
                $manager->persist($etatBrGr);
                // $promo = $promosRep->FindByGroupe($grp->getId())[0];
                $briefPromo = new BriefMaPromos;
                $briefPromo->setBrief($BriefObject);
                $briefPromo->setPromos($grp->getPromos());
                $manager->persist($briefPromo);
            }
        }
        $manager->flush();
        fclose($img);
        
        return $this->json("success",Response::HTTP_OK);


    }

    /**
     * @Route(
     * name="get_brief_brouillons_formateurs",
     * path="/api/formateurs/{id}/briefs/brouillons",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Brief:getBriefBrFormateur",
     * "_api_resource_class"=Brief::class,
     * "_api_collection_operation_name"="get_brief_brouillons_formateurs"
     * }
     * )
     */
    public function getBriefBrFormateur(int $id,BriefRepository $briefRepository)
    {
        $briefs= $briefRepository->getBriefBrouillons($id);

        foreach ($briefs as $item) {

            $item->setImagePromos( utf8_encode(base64_decode(stream_get_contents($item->getImagePromos()))));
        }

        return $this->json($briefs,Response::HTTP_OK,[],["groups"=>"briefbrouillon:read"]);

    }

    /**
     * @Route(
     * name="get_brief_valide",
     * path="/api/formateurs/{id}/briefs/valide",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Brief:getBriefValide",
     * "_api_resource_class"=Brief::class,
     * "_api_collection_operation_name"="get_brief_valide"
     * }
     * )
     */
    public function getBriefValide(int $id,BriefRepository $briefRepository)
    {
        $briefs = $briefRepository->getBriefFValid($id);

        foreach ($briefs as $item) {

            $item->setImagePromos(utf8_encode(base64_decode(stream_get_contents($item->getImagePromos()))));
        }

        return $this->json($briefs, Response::HTTP_OK, [], ["groups" => "briefV:read"]);

    }
    /**
     * @Route(
     *  name="put_brief",
     *  path="/api/formateurs/promo/{idPr}/brief/{idBr}",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::putBrief", 
     *      "_api_collection_operation_name"="put_brief"
     *  }
     * )
     */
    public function putBrief($idPr, $idBr, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager, BriefMaPromosRepository $rep)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Brief = $entityManager->getRepository(Brief::class)->find($idBr);
        $Promos = $entityManager->getRepository(Promos::class)->find($idPr);
        $JsonBrief= $request->getContent();
        $briefObject= $serializer->deserialize($JsonBrief,Brief::class,'json');
        if (!$Brief) {
            throw $this->createNotFoundException(
                'Pas de Brief Pour l\'id '.$idBr
            );
        }
        if (!$Promos) {
            throw $this->createNotFoundException(
                'Pas de Promos Pour l\'id '.$idPr
            );
        }
        $briefPromo = $rep->findBriefPromo($idBr, $idPr);
        if (!count($briefPromo)) {
            throw $this->createNotFoundException(
                'Le brief '.$idBr.' n\'est pas dans le promos'.$idPr
            );
        }
        if (count($briefObject->getNiveau())) {
            foreach($briefObject->getNiveau() as $niveau)
            {
                $Brief->addNiveau($niveau);
            }
            
        }
        else {
            return new JsonResponse("Ajouter un niveau",Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);
    }

    // public function putStatut($idPr, $idBr, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager, BriefMaPromosRepository $rep, EtatBriefGroupeRepository $etat)
    // {
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $Brief = $entityManager->getRepository(Brief::class)->find($idBr);
    //     $Promos = $entityManager->getRepository(Promos::class)->find($idPr);
    //     $JsonEtatBrief= $request->getContent();
    //     $etatBriefObject= $serializer->deserialize($JsonEtatBrief,EtatBriefGroupe::class,'json');
    //     if (!$Brief) {
    //         throw $this->createNotFoundException(
    //             'Pas de Brief Pour l\'id '.$idBr
    //         );
    //     }
    //     if (!$Promos) {
    //         throw $this->createNotFoundException(
    //             'Pas de Promos Pour l\'id '.$idPr
    //         );
    //     }
    //     $briefPromo = $rep->findBriefPromo($idBr, $idPr);
    //     if (!count($briefPromo)) {
    //         throw $this->createNotFoundException(
    //             'Le brief '.$idBr.' n\'est pas dans le promos'.$idPr
    //         );
    //     }
    //     $etatBrGr = $etat->findByEtBrGr($idBr);
    //     foreach($etatBrGr as $e){
    //         if ($etatBriefObject->getStatut()) {
    //             $e->setStatut($etatBriefObject->getStatut());
    //         }
    //     }
    //     $manager->persist($e);
    //     $manager->flush();
    //     return $this->json("success",Response::HTTP_OK); 
    // }

    /**
     * @Route(
     *  name="affecter_brief",
     *  path="/api/formateurs/promo/{idPr}/brief/{idBr}/assignation",
     *  methods={"PUT"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::affecterBrief",
     *      "_api_collection_operation_name"="affecter_brief"
     *  }
     * )
     */
    public function affecterBrief($idPr, $idBr, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager, BriefMaPromosRepository $rep, BriefApprenantRepository $brAp)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Brief = $entityManager->getRepository(Brief::class)->find($idBr);
        $Promos = $entityManager->getRepository(Promos::class)->find($idPr);
        $JsonObject = $request->getContent();
        $JsonObject = $serializer->decode($JsonObject,"json");
        if (!$Brief || !$Promos) {
            throw $this->createNotFoundException(
                'le Brief ou la promo n\'existe pas'
            );
        }
        $briefPromo = $rep->findBriefPromo($idBr, $idPr);
        if (!count($briefPromo)) {
            throw $this->createNotFoundException(
                'Le brief dont l\'id = '.$idBr.' n\'est pas dans le promos '.$idPr
            );
        }
        if (isset($JsonObject['groupe'])) {
            $Groupe = explode(",",$JsonObject['groupe']);
            foreach($Groupe as $grp){
                $grpObject = $entityManager->getRepository(Groupe::class)->find($grp);
                $etatBriefGroupe = new EtatBriefGroupe();
                $etatBriefGroupe->setGroupe($grpObject);
                $etatBriefGroupe->setBrief($Brief);
                foreach($grpObject->getApprenant() as $app){
                    dd($app);
                    $mail= (new \Swift_Message("Affectation Brief"));
                    $mail->setFrom("syfadilou3@gmail.com")
                        ->setTo($app->getEmail())
                        ->setSubject("Affectation d'un brief")
                        ->setBody("Bonjour Cher(e) ".$app->getFirstName()." ".$app->getLastName()."
                        le brief ".$Brief->getNomBrief()." vous a été affecter")
                        ;

                        $this->mailer->send($mail);
                }
            $manager->persist($etatBriefGroupe);
            }
        }
        if (isset($JsonObject['apprenant'])) {
            $Apprenant = $entityManager->getRepository(Apprenant::class)->find($JsonObject['apprenant']);
            if (count($brAp->ifApprenantBrief($Apprenant->getId(), $briefPromo[0]->getId()))) {
                return $this->json("Brief déja assigner à cette apprenant");
            }
            $BriefApprenant =  new BriefApprenant();
            $BriefApprenant->setApprenant($Apprenant);
            $BriefApprenant->setBriefMaPromo($briefPromo[0]);
            $mail= (new \Swift_Message("Affectation Brief"));
              $mail->setFrom("syfadilou3@gmail.com")
                  ->setTo($Apprenant->getEmail())
                  ->setSubject("Affectation d'un brief")
                  ->setBody("Bonjour Cher(e) ".$Apprenant->getFirstName()." ".$Apprenant->getLastName()."
                  le brief ".$Brief->getNomBrief()." vous a été affecter")
                  ;
                  $this->mailer->send($mail);
            $manager->persist($BriefApprenant);
            
        }else {
            if (isset($JsonObject['dsf_apprenant'])) {
                $Apprenant = $entityManager->getRepository(Apprenant::class)->find($JsonObject['dsf_apprenant']);
                $brmapromo = $brAp->ifApprenantBrief($Apprenant->getId(), $briefPromo[0]->getId())[0];
                if (!$brmapromo) {
                    return $this->json("Brief pas assigner à cette apprenant");
                }
                $entityManager->remove($brmapromo);
            }
        }
        
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);
        
        
    }

    /**
     * @Route(
     * name="get_briefbyPromos",
     * path="/api/formateurs/promo/{id_p}/briefs/{id_b}",
     * name="get_brief_promos",
     * path="/formateurs/promo/{id_p}/briefs/{id_b}",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Brief:getBriefPromos",
     * "_api_resource_class"=Brief::class,
     * "_api_collection_operation_name"="get_briefbyPromos"
     * }
     * )
     */
    public function getBriefPromos(int $id_p, int $id_b,BriefRepository $briefRepository)
    {
        $briefs = $briefRepository->getBriefOfPromos($id_p, $id_b);

        foreach ($briefs as $item) {

            $item->setImagePromos(utf8_encode(base64_decode(stream_get_contents($item->getImagePromos()))));
        }

        return $this->json($briefs, Response::HTTP_OK, [], ["groups" => "briefinP:read"]);
    }

    /**
     * @Route(
     *  name="add_url",
     *  path="/api/apprenants/{idAp}/groupe/{idGr}/livrables",
     *  methods={"POST"},
     *  defaults={
     *      "_controller"="\app\Controller\Brief::AddUrl",
     *      "_api_collection_operation_name"="add_url"
     *  }
     * )
     */
    public function AddUrl($idAp, $idGr, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager, ApprenantRepository $rep, LivrableAttenduApprenantRepository $LA_ApRep)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Apprenant = $entityManager->getRepository(Apprenant::class)->find($idAp);
        $Groupe = $entityManager->getRepository(Groupe::class)->find($idGr);
        $JsonAPlivrableAtt= $request->getContent();
        $APlivrableAttObject= $serializer->deserialize($JsonAPlivrableAtt,LivrableAttenduApprenant::class,'json');
        if (!$Apprenant) {
            throw $this->createNotFoundException(
                'Pas de Apprenant Pour l\'id '.$idAp
            );
        }
        if (!$Groupe) {
            throw $this->createNotFoundException(
                'Pas de Groupe Pour l\'id '.$idGr
            );
        }
        if(!count($rep->ifApprenantGroupe($idAp, $idGr))){
            throw $this->createNotFoundException(
                'L\'apprenant dont l\'id = '.$idAp.' n\'est pas dans le Groupe '.$idGr
            );
        }
        $app = $rep->findByGroupe($idGr);
        foreach($app as $apprenant){
            $LA_ap = $LA_ApRep->findByApprenant($apprenant->getId());
            if (count($LA_ap)) {
                foreach($LA_ap as $key){
                    $key->setUrl($APlivrableAttObject->getUrl());
                }
            }
            
        }
        $manager->flush();
        return $this->json("success",Response::HTTP_OK);

    }
}
