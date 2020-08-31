<?php

namespace App\Controller;

use App\Repository\GroupeCompetencesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupeCompetences;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeCompetencesController extends AbstractController
{
    /**
     * @Route(
     * name="get_competences_by_group_id",
     * path="/api/admin/grpecompetences/{id}/competences",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\GroupeCompetences::getCompetencesByGroupId",
     * "_api_resource_class"=GroupeCompetences::class,
     * "_api_collection_operation_name"="get_competences_by_group_id"
     * }
     * )
     */
    public function getCompetencesByGroupId(int $id,GroupeCompetencesRepository $repository)
    {
        $competences= $repository->getCompetencesByGroupId($id);
        return $this->json($competences,Response::HTTP_OK);
    }

    /**
     * @Route(
     * name="post_groupe_competences",
     * path="/api/admin/grpecompetences",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\GroupeCompetences::postGroupeCompetences",
     * "_api_resource_class"=GroupeCompetences::class,
     * "_api_collection_operation_name"="post_groupe_competences"
     * }
     * )
     */
    public function postGroupeCompetences(SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
        $jsonGroupC= $request->getContent();


        $groupCObject= $serializer->deserialize($jsonGroupC,GroupeCompetences::class,'json');

        $manager->persist($groupCObject);
        $manager->flush();
        return new JsonResponse('success', Response::HTTP_CREATED,[],'true');
    }

    /**
     * @Route(
     * name="get_gComp_Comp",
     * path="/api/admin/referentiels/grpecompetences",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\GroupeCompetences::getGrpeComp_Comp",
     * "_api_resource_class"=GroupeCompetences::class,
     * "_api_collection_operation_name"="get_gComp_Comp"
     * }
     * )
     */
    public function getGrpeComp_Comp(GroupeCompetencesRepository $repository)
    {
        $grpeCompetences= $repository->getGcompCompetences();
        return $this->json($grpeCompetences,Response::HTTP_OK);
    }

}
