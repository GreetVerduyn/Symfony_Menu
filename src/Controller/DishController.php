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
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $dish = new Dishes();

        //Form
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // EntityManager
            $em = $doctrine->getManager();
            $image = $form->get('image')->getData();

            if ($image) {
                $filename = md5(uniqid()) . '.' . $image->guessClientExtension();
            }

            $image->move(
                $this->getParameter('images_folder'),
                $filename
            );

            $dish->setImage($filename);
            $em->persist($dish);

            //execute the queries(INSERT)
            $em->flush();
            return $this->redirect($this->generateUrl('app_dish_edit'));
        }

        //Response
        return $this->render('dish/create.html.twig', [
            'createForm' => $form->createView()]);
    }


    #[Route('/delete/{id}', name: '_delete')]                       // route = /dish/delete    name = app_dish_delete
    public function delete(ManagerRegistry $doctrine, $id, DishesRepository $dr)
    {
        // EntityManager
        $em = $doctrine->getManager();
        $dish = $dr->find($id);
        $em->remove($dish);

        //execute the queries(DELETE)
        $em->flush();

        //message
        $this->addFlash('deleted', 'Dish is removed');

        return $this->redirect($this->generateUrl('app_dish_edit'));
    }

    #[Route('/show/{id}', name: '_show')]                  // route = /dish/show    name = app_dish_show
    public function show(Dishes $dish)
    {

        dump ($dish);
       return $this->render('dish/show.html.twig', [
           'dish' => $dish
      ]);

    }

}
