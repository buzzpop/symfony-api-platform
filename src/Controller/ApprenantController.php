<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\GroupeRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Groupe;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Apprenant;

class ApprenantController extends AbstractController
{
    private $serializer;
    private $repository;
    private $validator;
    public function  __construct(SerializerInterface $serializer, UserRepository $repository,ValidatorInterface $validator)
    {
        $this->serializer= $serializer;
        $this->repository= $repository;
        $this->validator= $validator;
    }

    /**
     * @Route(
     * name="get_apprenant",
     * path="/api/apprenants",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\UsersByProfil::getApprenant",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="get_apprenant"
     * }
     * )
     */
    public function getApprenant()
    {
        $apprenant= $this->repository->findApprenant("Apprenant");
        return $this->json($apprenant,Response::HTTP_OK);
    }

    /**
     * @Route(
     * name="post_apprenant",
     * path="/api/apprenants",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\UsersByProfil::postApprenant",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="post_apprenant"
     * }
     * )
     */
    public function postApprenant(Request $request, EntityManagerInterface $manager)
    {
       $jsonApprenant= $request->getContent();


        $apprenantObject= $this->serializer->deserialize($jsonApprenant,User::class,'json');

        $errors = $this->validator->validate($apprenantObject);
        if (count($errors) > 0) {
            $errorsString =$this->serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($apprenantObject);
        $manager->flush();
        return new JsonResponse('success', Response::HTTP_CREATED,[],'true');
    }

    /**
     * @Route(
     * name="get_apprenant_by_id",
     * path="/api/apprenants/{id}",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\UsersByProfil::getApprenantById",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="get_apprenant_by_id"
     * }
     * )
     */
    public function getApprenantById(int $id )
    {
        $apprenant= $this->repository->findApprenantById($id);
        return $this->json($apprenant,Response::HTTP_OK);
    }

    /**
     * @Route(
     * name="get_apprenants_by_groupes",
     * path="/api/admin/{groupes}/apprenants",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Apprenant::getApprenantByGroupe",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="get_apprenants_by_groupes"
     * }
     * )
     */
    public function getApprenantByGroupe(string $groupes, UserRepository $repository)
    {
        $apprenant= $repository->getApprenantByGroup($groupes, "Apprenant");
        return $this->json($apprenant,Response::HTTP_OK);
    }

    /**
     * @Route(
     * name="delete_apprenant",
     * path="/api/admin/groupes/{id}/apprenant/{Id}",
     * methods={"DELETE"},
     * defaults={
     * "_controller"="\app\Controller\Apprenant::delete_apprenant",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="delete_apprenant"
     * }
     * )
     */
    public function delete_apprenant(int $id, int $Id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Groupe = $entityManager->getRepository(Groupe::class)->find($id);
        $User = $entityManager->getRepository(User::class)->find($Id);
        if (!$Groupe) {
            throw $this->createNotFoundException(
                'Pas de Groupe Pour l\'id '.$id
            );
        }
        if (!$User) {
            throw $this->createNotFoundException(
                'Pas d\'utilisateur Pour l\'id '.$Id
            );
        }
        if ($User->getProfil()->getLibelle() !== "Apprenant") {
            throw $this->createNotFoundException(
                'User dont l\'id='.$Id.' n\'est pas un Apprenant'
            );
        }
        if (!($Groupe->removeUser($User)->getUsers()->isDirty())) {
            return new JsonResponse('l\'Apprenant dont l\'id = '.$Id.' n\'existe pas dans le groupe', Response::HTTP_CREATED,[],'true');
        }
        $entityManager->flush();
        return new JsonResponse('success', Response::HTTP_CREATED,[],'true');
    }

}
