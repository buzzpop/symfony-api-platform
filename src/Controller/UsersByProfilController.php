<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;

class UsersByProfilController extends AbstractController
{
    /**
     * @Route(
     * name="users_by_profil",
     * path="/api/admin/profils/{id}/users",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\UsersByProfil::getUsersByProfil",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="get_users_by_profil"
     * }
     * )
     */
    public function getUsersByProfil(Request $request,UserRepository $repository, int $id ): Paginator
    {
        $page = (int) $request->query->get('page', 1);
      return $repository->findUsersByProfil($id,$page);

    }

    /**
     * @Route(
     * name="post_users",
     * path="/api/admin/users",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\UsersByProfil::postUsers",
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="post_users"
     * }
     * )
     */
    public function postUsers(Request $request,UserRepository $repository)
    {
        $user = $request->request->all();
        dd($request);



    }
}
