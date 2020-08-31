<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class FormateurController extends AbstractController
{
    /**
     * @Route(
     * name="get_formateur_by_id",
     * path="/api/formateurs/{id}",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\UsersByProfil::getFormateurById",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="get_formateur_by_id"
     * }
     * )
     */
    public function getFormateurById($id,UserRepository $repository)
    {
        $formateur= $repository->findFormateurById($id);
        return $this->json($formateur,Response::HTTP_OK);
    }

    /**
     * @Route(
     * name="get_formateurs",
     * path="/api/formateurs",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\UsersByProfil::getFormateurs",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="get_formateurs"
     * }
     * )
     */
    public function getFormateurs( UserRepository $repository)
    {
        $apprenant= $repository->findFormateurs('Formateur');
       
        return $this->json($apprenant,Response::HTTP_OK);
    }

}
