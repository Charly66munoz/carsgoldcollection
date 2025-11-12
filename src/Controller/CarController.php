<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Form\Entity\CarForm;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class CarController extends AbstractController
{

    

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        
        return $this->render('car/home.html.twig', []);
    }

    #[Route('/cars', name: 'cars')]
    public function indexs(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll(); // buscara todo los coches


        return $this->render('car/listCars.html.twig', [
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
    public function carAdd(CarRepository $carRepository, Request $request, EntityManagerInterface $em): Response
    {
        $car = new Car();
        $form = $this->createForm(CarForm::class, $car);
        $cars = $carRepository->findAll(); // buscara todo los coches

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em ->persist($car);
            $em->flush();

            return $this->redirectToRoute('cars',[], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/new.html.twig', [
        'form' => $form,
        'cars' => $cars,
        ]);
    }

    #[Route('cars/{id}/update', name: 'edit_car')]
    public function carEdit(Request $request, EntityManagerInterface $em, Car $car): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('car_detail',['id'=> $car->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('car/editCar.html.twig',[
            'car'=> $car,
            'form' => $form,
        ]);
    }

    /**
     * @Method("DELETE")
    */
    #[Route('/{id}', name: 'delete_car', methods: ['POST'],)]
    public function carDelete(Request $request, Car $car, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('delete'.$car->getId(), $request->getPayload()->getString('_token'))){
            $em->remove($car);
            $em->flush();
        }

        return $this->redirectToRoute('cars',[],Response::HTTP_SEE_OTHER);


    }

    



    //list ok
    //C R U D
    //Create ok..
    //Read ok
    //Update ..
    //Delete ..

   
}
