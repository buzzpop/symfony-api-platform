<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CmController extends AbstractController
{
    /**
     * @Route("/cm", name="cm")
     */
    public function index()
    {
        return $this->render('cm/index.html.twig', [
            'controller_name' => 'CmController',
        ]);
    }
}
