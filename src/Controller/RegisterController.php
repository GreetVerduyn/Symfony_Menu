<?php

namespace App\Controller;


use Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): Response
    {

        $regform = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Employee'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('register', SubmitType::class)
            ->getForm();

        $regform->handleRequest($request);

        if ($regform->isSubmitted()) {
            $input = $regform->getData();
            $user = new User();
            $user->setUsername($input['username']);
            $user->setPassword(
                $passwordHasher->hashPassword($user, $input['password'])
            );

            //EntityManager
            $em = $doctrine->getManager();
            $em ->persist($user);
            $em -> flush();

            return $this ->redirect($this ->generateUrl('app_home'));

        }

        return $this->render('register/index.html.twig', [
            'regform' => $regform->createView()
        ]);
    }
}
