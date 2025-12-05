<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\Entity\CarForm;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        $cars = $carRepository->findAll(); 
        $user = $this->getUser();
        $roles = $user ? $user->getRoles() : [];
        return $this->render('car/listCars.html.twig', [
            'cars' => $cars,
            'role' => $roles,
            
        ]);
    }

    #[Route('/miCars', name: 'mis_autos')]
    public function miCars(CarRepository $carRepository){

        $user = $this->getUser();
        $roles = $user ? $user->getRoles() : [];

        if ($user) {
            $misCars = $carRepository->findBy(['owner' => $user]);
            return $this->render('car/listCars.html.twig', [
                'cars' => $misCars,
                'role' => $roles,
            ]);
        }
    }
    
    #[Route('/cars/car/{id}', name: 'car_detail')]
    public function carDetail(Uuid $id, CarRepository $carRepository): Response
    {
        $car = $carRepository->find($id); 
        $cars = $carRepository->findAll(); 
        
        $user = $this->getUser();
        /** @var \App\Entity\Car $car */
        $verf = false;
        if ($user){
            $verf = ($car->getOwner()  === $user ) ? true : false;
        }
        $roles = $user ? $user->getRoles() : [];

        
        return $this->render('car/carDetail.html.twig', [
            'role' => $roles,
            'car' => $car,
            'cars' => $cars,
            'verf' => $verf,
        ]);
    }

    #[Route('/cars/new', name: 'add_car')]
    public function carAdd(CarRepository $carRepository, Request $request): Response
    {
        $car = new Car();
        $form = $this->createForm(CarForm::class, $car, ['validation_groups' => ['create'],]);
        $cars = $carRepository->findAll(); 
        $form->handleRequest($request);
        $user = $this->getUser();


        $photoFile = $form->get('photoFile')->getData();
        if ($photoFile) {
        $newFileName = uniqid().'.'.$photoFile->guessExtension();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            
            try {
            $photoFile->move($this->getParameter('photo_directory'), $newFileName);
            $car->setPhoto($newFileName);
            } catch (FileException $e) {
            $this->addFlash('error', 'No se pudo subir la imagen. Intenta de nuevo.');
            }
            $car->setOwner($user);

            $carRepository->save($car, true);

            return $this->redirectToRoute('cars',[], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/new.html.twig', [
        'form' => $form,
        'cars' => $cars,
        ]);
    }

    #[Route('cars/{id}/update', name: 'edit_car')]
    public function carEdit(Request $request, CarRepository $carRepository, Car $car): Response
    {
        $user = $this->getUser();

        // Comprobamos que solo el dueño pueda editar
        if (!$user || $car->getOwner() !== $user) {
            throw $this->createAccessDeniedException('No tienes permiso para editar este coche.');
        }

        $oldPhoto= $car->getPhoto();
        $form = $this->createForm(CarForm::class, $car);
        $form->handleRequest($request);
        $fileSystem = new Filesystem;

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photoFile')->getData();

            if ($photoFile) {
                $newFileName = uniqid().'.'.$photoFile->guessExtension();
                try {
                    $photoFile->move($this->getParameter('photo_directory'), $newFileName);
                    $fileSystem->remove($this->getParameter('photo_directory').'/'.$oldPhoto);
                    $car->setPhoto($newFileName);
                } catch (FileException $e) {
                    $this->addFlash('error', 'No se pudo subir la imagen. Intenta de nuevo.');
                }
            } else {
                $car->setPhoto($oldPhoto);
            }
            $carRepository->save($car , true );

            return $this->redirectToRoute('car_detail',['id'=> $car->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/editCar.html.twig',[
            'car'=> $car,
            'form' => $form,
        ]);
    }
    
    #[Route('/cars/{id}/delete', name: 'delete_car', methods: ['POST'],)]
    public function carDelete(Request $request, Car $car, CarRepository $carRepository): Response
    {
        $user = $this->getUser();

        if (!$user || $car->getOwner() !== $user) {
            throw $this->createAccessDeniedException('No tienes permiso para eliminar este coche.');
        }

        $fileSystem = new Filesystem;
        if($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))){
            $fileSystem->remove($this->getParameter('photo_directory').'/'.$car->getPhoto());
            $carRepository->remove($car, true);
        }

        return $this->redirectToRoute('cars',[],Response::HTTP_SEE_OTHER);
    }
}
