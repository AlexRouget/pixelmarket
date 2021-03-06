<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $response = new Response();

        $form = $this->createForm(RegistrationFormType::class);

        $form->handleRequest($request);

        // gérer les données recues
        if ($form->isSubmitted() && $form->isValid()) {

            // créer un user
            $user = $form->getData();

            /* @var UploadedFile $file */
            $file = $user->getAvatar();

                if (!empty($file)) {
                    $basename = 'post-attachment-' . md5(uniqid());
                    // c'est une identifiant basé sur la date en microseconde. c'est n'est pas utilisable en secu, de plus on peut avoir la même chaîne de caractère
                    $ext = $file->guessExtension();
                    $filename = $basename . '.' . $ext;

                    $file->move($this->getParameter('user_upload_folder'), $filename);
                    $user->setAvatar($filename);
                }
                $user->setChecked(false);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->getDoctrine()->getManager()->flush();

            // on dit à Doctrine de "s'occuper" de ce Post
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);

            // finalement, on dit au manager d'envoyer le post en BDD
            $manager->flush();

            //  MESSAGE FLASHE
            $this->addFlash('notice', 'Bienvenue dans la communauté des pixelmarkets!');        
            $this->addFlash('success', 'Votre compte à bien été enregistré. Connecte-toi !');

            return $this->redirectToRoute('app_login');
            sleep(5);
        }


        return $this->render('registration/register.html.twig', 
        ['register_form' => $form->createView(), 
        'title' => 'Formulaire d\'enregistrement',
        ]);
    }
    
}