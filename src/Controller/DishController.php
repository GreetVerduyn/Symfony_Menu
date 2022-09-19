<?php

namespace App\Controller;

use App\Entity\Dishes;
use App\Form\DishType;
use App\Repository\DishesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/dish', name: 'app_dish')]
class DishController extends AbstractController
{
    #[Route('/', name: '_edit')]                                   // route = /dish/     name= app_dish_edit
    public function index(DishesRepository $dr): Response
    {
        $dishes = $dr->findAll();
        return $this->render('dish/index.html.twig', [
            'dishes' => $dishes,
        ]);
    }

    #[Route('/create', name: '_create')]                             // route = /dish/create    name = app_dish_create
    public function create(ManagerRegistry $doctrine, Request $request) : Response
    {
        $dish = new Dishes();

        //Form
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form ->isSubmitted()){
            // EntityManager
            $em = $doctrine ->getManager();
            $em ->persist($dish);

            //execute the queries(INERT)
            $em ->flush();
            return $this->redirect($this->generateUrl('app_dish_edit'));

        }

        //Response
        return $this->render('dish/create.html.twig', [
            'createForm' => $form->createView()]);
    }

}
