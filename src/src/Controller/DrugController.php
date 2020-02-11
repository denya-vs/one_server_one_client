<?php

namespace App\Controller;

use App\Entity\Drug;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DrugController extends AbstractController
{
    /**
     * @Route("/drug/{id_disease}", name="drug")
     * @param $id_disease
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index($id_disease)
    {
        $drugs = $this->getDoctrine()
            ->getRepository(Drug::class)
            ->findByDisease($id_disease);

        return $this->json($drugs);
    }
}
