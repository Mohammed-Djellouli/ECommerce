<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        //create the form from RegisterUserType
        $form = $this->createForm(RegisterUserType::class,$user);
        $form->handleRequest($request);

        //si le formulaire est soumis
        if($form->isSubmitted() && $form->isValid()){
            //si le données sont valides
            //récupération de informations entrer
            $formData = $form->getData();

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre compte est correctement créer'
            );

            //Mail de confirmation d'inscription
            $mail = new Mail();
            $variables = [
                'firstname' => $user->getFirstname(),
            ];
            $mail->send($user->getEmail(),$user->getFirstname().' '.$user->getLastname(),"Bienvenue","welcome",$variables);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig',[
            'registerForm' => $form->createView(),
        ]);
    }
}
