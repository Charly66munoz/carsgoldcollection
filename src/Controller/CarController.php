<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll(); // buscara todo los coches


        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/car/{id}', name: 'car_detail')]
    public function carDetail( $id, CarRepository $carRepository): Response
    {
       

        $car = $carRepository->find($id); // buscara todo los coches


        return $this->render('car/carDetail.html.twig', [
            'car' => $car,
        ]);
    }
}
