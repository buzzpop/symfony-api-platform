<?php

namespace App\Controller;

use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupeTag;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeTagController extends AbstractController
{
    /**
     * @Route(
     * name="put_tags",
     * path="/api/admin/grptags/{id}",
     * methods={"PUT"},
     * )
     */
    public function putTagsInGroupeTags(EntityManagerInterface $entityManager, int $id, Request $request,  SerializerInterface $serializer,GroupeTagRepository $repository )
    {
        $groupeTags= $request->getContent();
        $groupeTagsObj= $serializer->deserialize($groupeTags,GroupeTag::class, 'json');
        if (count($groupeTagsObj->getTags())==0){
            return $this->json('ajouter au moins un tags',Response::HTTP_BAD_REQUEST);
        }

        $grouptagId= $repository->find($id);

        for ($i=0;$i<count($groupeTagsObj->getTags());$i++){

           if (in_array($groupeTagsObj->getTags()[$i],array($grouptagId->getTags()))){
               return $this->json('le tag existe deja',Response::HTTP_BAD_REQUEST);
           }else{
               return $this->json('le tag n\'existe pas',Response::HTTP_BAD_REQUEST);
           }
        }
        $entityManager->flush();

        return $this->json('modification reussie',Response::HTTP_OK);
    }
}
