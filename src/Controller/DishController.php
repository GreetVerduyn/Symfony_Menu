<?php

namespace App\Controller;

use App\Entity\Dish;use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/dish', name: 'app_dish')]
class DishController extends AbstractController
{
    #[Route('/', name: 'app_edit')]                                   // route = /dish/
    public function index(): Response
    {
        return $this->render('dish/index.html.twig', [
            'controller_name' => 'DishController',
        ]);
    }

    #[Route('/create', name: 'app_create')]                             // route = /gitdish/create
    public function create(Request $request)
    {
        $dish = new Dish();
        $dish ->setName('Pizza');

        // EntityManager
        $em = $this ->getDoctrine() ->getManager();
        $em ->persist($dish);
        $em ->flush();

    }

}
