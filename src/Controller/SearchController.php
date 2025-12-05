<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search/ajax', name: 'search_ajax')]
    public function searchAjax(Request $request, CarRepository $carRepository): JsonResponse
    {
        //reques coentiene toda informacion de la peticion HTTP
       
        $query = $request->query->get('q', '');
        $cars = $carRepository->getAdminList($query);

        $results = [];
        foreach ($cars as $car) {
            $results[] = [
                'id' => $car->getId(),
                'brand' => $car->getBrand(),
                'model' => $car->getModel(),
                'year' => $car->getYear(),
            ];
        }

        return $this->json($results);
    }
}