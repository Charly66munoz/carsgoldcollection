<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Filesystem\Filesystem;



class AdminController extends AbstractController
{
    #[Route('/admin', 'dashboard')]
    public function index(): Response
    {
        return $this->render('user/dashboard.html.twig', []);

    }

    #[Route('/admin/ulist', 'users')]
    public function userList(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/userList.html.twig',[
            'users' => $users,
        ]);
    }
    
    #[Route('/admin/{id}/', 'userDetail')]
    public function userDetail(Uuid $id , UserRepository $userRepository, CarRepository $carRepository): Response
    {
        $usern = $userRepository->find($id);

        $cars = $carRepository->findBy(
                ['owner' => $id]
            );
        $ncars = count($cars);

        $carsArray = array_map(fn($car) => [
        'id' => $car->getId(),
        'brand' => $car->getBrand(),
        'model' => $car->getModel(),
        'year' => $car->getYear(),
        'imagen' => $car->getPhoto(),
        ], $cars);

        return $this->render('user/userDetail.html.twig',[
            'user' => $usern,
            'cars' => $carsArray,
            'ncars' => $ncars,
        ]);
    }

    #[Route('/user/{id}/delete', name: 'delete_user', methods: ['POST'],)]
    public function carDelete(Request $request, User $users, UserRepository $userRepository, CarRepository $carRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('No tienes permiso para eliminar este coche.');
        }
        $fileSystem = new Filesystem;
        
        if($this->isCsrfTokenValid('delete'.$users->getId(), $request->request->get('_token'))){
            
            
            $cars = $carRepository->findBy(
                ['owner' => $users->getId()]
            );
            
            foreach ($cars as $car){
                $fileSystem-> remove($this->getParameter('photo_directory').'/'.$car->getPhoto());
                $carRepository->remove($car, true);
            }


            $userRepository->remove($users, true);
        }

        return $this->redirectToRoute('users',[],Response::HTTP_SEE_OTHER);
    }
}