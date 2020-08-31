<?php

namespace App\Controller;


use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competences;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompetencesController extends AbstractController
{
    private $serializer;

    public  function __construct(SerializerInterface $serializer)
    {
        $this->serializer= $serializer;
    }

    /**
     * @Route(
     * name="post_competence",
     * path="/api/admin/competences",
     * methods={"POST"},
     * defaults={
     * "_controller"="\app\Controller\Competences::addCompetence",
     * "_api_resource_class"=Competences::class,
     * "_api_collection_operation_name"="post_competence"
     * }
     * )
     */
    public function addCompetence(ValidatorInterface $validator, Request $request, EntityManagerInterface $manager, NiveauRepository $repo)
    {
        $jsonCompetences= $request->getContent();
        $competence = new Competences();

        $competenceTab= $this->serializer->decode($jsonCompetences,'json');

        $libelle= $competenceTab['libelle'];

       // dd($libelle);
        $entityManager= $this->getDoctrine()->getManager();
        $findCompetence= $entityManager->getRepository(Competences::class)->findBy(
            [
            "libelle"=>$libelle
            ]
        );
        //dd(gettype($findCompetence));
       if ($findCompetence){
           return new JsonResponse('La competence '.$libelle.' existe d\'éjà. Ajouter un autre', Response::HTTP_CREATED,[],'true');
       }
        if (!isset($competenceTab['niveaux'])){
            return new JsonResponse('Ajouter les niveaux pour cette competences', Response::HTTP_BAD_REQUEST,[],'true');
        }

        if (count($competenceTab['niveaux'])!=3){
            return new JsonResponse('La competence "'.$libelle.'" doit avoir 3 niveaux', Response::HTTP_BAD_REQUEST,[],'true');
        }

        $niveaux = $competenceTab['niveaux'];



        foreach ($niveaux as $niveau){

        $niv= $this->serializer->denormalize($niveau,"App\Entity\Niveau",'json');

       $error= $validator->validate($niv);
            if (count($error)){
                return $this->json($error,Response::HTTP_BAD_REQUEST);
            }

          $manager->persist($niv);
            $competence->addNiveau($niv);

        }
        $competence->setLibelle($competenceTab['libelle']);

        $errors= $validator->validate($competence);
        if (count($errors) > 0){
            $stringError= $this-> serializer->serialize($errors,'json');
            return new JsonResponse($stringError,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($competence);
        $manager->flush();
        return new JsonResponse('Competence créée', Response::HTTP_CREATED,[],'true');
    }

    /**
     * @Route(
     * name="put_competence",
     * path="/api/admin/competences/{id}",
     * methods={"PUT"},
     * defaults={
     * "_controller"="\app\Controller\Competences::putCompetence",
     * "_api_resource_class"=Competences::class,
     * "_api_collection_operation_name"="put_competence"
     * }
     * )
     */

    public  function putCompetence(int $id, ValidatorInterface $validator, Request $request, EntityManagerInterface $manager){

        $jsonCompetences= $request->getContent();

        $competenceTab= $this->serializer->decode($jsonCompetences,'json');
        dd($competenceTab);

    }
}
