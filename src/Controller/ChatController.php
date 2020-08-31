<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Repository\ChatRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromosRepository;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
     /**
     * @Route(
     * name="commentaire",
     * path="/api/users/promo/{id}/apprenant/{id1}/chats",
     * methods={"GET"},
     * defaults={
     * "_controller"="\app\Controller\Chat::getChat",
     * "_api_resource_class"=Chat::class,
     * "_api_collection_operation_name"="get_commentaire"
     * }
     * )
     */
    public function getChat(ApprenantRepository $appRepository,ChatRepository $chatRepository,PromosRepository $promoRepository,$id,$id1){
      $promo = $promoRepository->find($id);
      $apprenant = $appRepository->find($id1);
      if (!($promo && $apprenant)) {
          return $this->json("l'id du promo ou de l'apprenant  n'existe pas", Response::HTTP_BAD_REQUEST);
      }else{
          $chats[] = $chatRepository->findChat($id,$id1);
          //dd($chats);
      }


      return $this->json($chats, Response::HTTP_OK, []);
  }


     /**
     * @Route(
     * name="post_commentaire",
     * path="/api/users/promo/{id}/apprenant/{id1}/chats",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\Chat::addChat",
     * "_api_resource_class"=Chat::class,
     * "_api_collection_operation_name"="post_commentaire"
     * }
     * )
     */
    public function addChat(int  $id, int $id1,ApprenantRepository $apprenantRepository,GroupeRepository $groupeRepository,SerializerInterface $serializer,Request $request, EntityManagerInterface $manager)
    {
            $jsonGroupC= json_decode($request->getContent(),true);
            if (!isset($jsonGroupC['message'])){
                return new JsonResponse("Veuillez renseigner un message", Response::HTTP_BAD_REQUEST, [], true);
            }

        $groupe= $groupeRepository->findOneBy(
            [
               "promos"=>$id
            ]
        );
        if (!$groupe){
            return new JsonResponse("Le promos dont l'id=" . $id . "n'existe pas", Response::HTTP_BAD_REQUEST, [], true);
        }

            foreach ($groupe->getApprenant() as $apprenant){
                if ($apprenant->getId() == $id1){
                    $promos=$groupe->getPromos();
                    $apprenant= $apprenantRepository->findOneBy(["id"=>$id1]);
                $chat= new  Chat();
                $chat->setMessage($jsonGroupC['message']);
                if ($jsonGroupC['pieceJointes']){
                    $chat->setPieceJointes($jsonGroupC['pieceJointes']);
                }
                $chat->setDate(new \DateTime())
                    ->setPromos($promos)
                    ->setUser($apprenant);

                }else{
                    return new JsonResponse("L'apprenant dont l'id=" . $id . "n'est pas dans le promos", Response::HTTP_BAD_REQUEST, [], true);
                }
            }



        $manager->persist($chat);
        $manager->flush();
        return new JsonResponse('chat enregistré', Response::HTTP_CREATED,[],'true');
    }

}
