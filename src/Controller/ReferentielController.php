<?php

namespace App\Controller;

use App\Repository\ReferentielsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use  App\Entity\Referentiels;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     * name="get_competences_by_group_id_by_ref_id",
     * path="/api/admin/referentiels/{idr}/grpecompetences/{idg}",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Referentiel::getCompetencesByGroupIdByRefId",
     * "_api_resource_class"=Referentiels::class,
     * "_api_collection_operation_name"="get_competences_by_group_id_by_ref_id"
     * }
     * )
     */
    public function getCompetencesByGroupIdByRefId(int $idr,int $idg,ReferentielsRepository $repository)
    {
        $competences= $repository->getCompetencesByGroupIdByRefId($idr,$idg);
        return $this->json($competences,Response::HTTP_OK);
    }

    /**
     * @Route(
     * name="post_ref",
     * path="/api/admin/referentiels",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\Referentiel::postRef",
     * "_api_resource_class"=Referentiels::class,
     * "_api_collection_operation_name"="post_ref"
     * }
     * )
     */
    public function postRef(ValidatorInterface $validator, SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
        $noValidates=[];
        $jsonGroupC= $request->getContent();

        $groupCObject= $serializer->deserialize($jsonGroupC,Referentiels::class,'json');
        $groupeCompetences=$groupCObject->getGroupeCompetences();

        for ($i=0;$i< count($groupeCompetences);$i++){


            for ($j=0;$j< count(array($competence=$groupeCompetences[$i]->getCompetence()));$j++){

                    //dd(count($competence[$j]->getNiveaux()));

                    if (count($competence[$j]->getNiveaux())!=3){
                        $noValidates[]='Ajouter 3 Niveau pour la  Competence '.$competence[$j]->getLibelle();

                    }
            }

        }

        if (count($noValidates)!=0){
            $jsonError= $serializer->encode($noValidates,'json');
            return new JsonResponse($jsonError, Response::HTTP_BAD_REQUEST,[],'true');
        }

        $errors= $validator->validate($groupCObject);
        if (count($errors) > 0){
            $stringError= $serializer->serialize($errors,'json');
            return new JsonResponse($stringError,Response::HTTP_BAD_REQUEST,[],true);
        }

        $manager->persist($groupCObject);
        $manager->flush();
        return new JsonResponse('referentiel créé!', Response::HTTP_CREATED,[],'true');
    }


}
