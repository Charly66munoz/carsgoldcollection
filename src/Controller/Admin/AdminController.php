<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', 'dashboard')]
    public function index(): Response
    {
        return $this->render('user/dashboard.html.twig', []);

    }

    #[Route('admin', 'user_list')]
    public function userList(UserRepository $userRepository): Response
    {



    }





}