<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\Entity\CarForm;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class CarController extends AbstractController
{
    #[Route('/', name: 'home')]
    #[Route('/cars', name: 'cars')]
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll(); // buscara todo los coches


        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/cars/car/{id}', name: 'car_detail')]
    public function carDetail(Uuid $id, CarRepository $carRepository): Response
    {
       

        $car = $carRepository->find($id); // buscara todo los coches
        $cars = $carRepository->findAll(); // buscara todo los coches


        return $this->render('car/carDetail.html.twig', [
            'car' => $car,
            'cars' => $cars,
        ]);
    }

    #[Route('/cars/new', name: 'add_car')]
    public function carAdd(CarRepository $CarRepository){
        $car = new Car();

         $form = $this->createForm(CarForm::class, $car);
        
    }

    /**
     * @Method("DELETE")
    */
    #[Route('cars/{id}/delete', name: 'delete')]
    public function carDelete(CarRepository $CarRepository){

    }

    #[Route('cars/{id}/update', name: 'edit')]
    public function carEdit(CarRepository $carRepository){

    }



    //list ok
    //C R U D
    //Create ..
    //Read ok
    //Update ..
    //Delete ..

   
}
