<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users/{id<\d+>}", name="user_profile")
     */
    public function profile($id)
    {
        if(intval($id) === $this->getUser()->getId()) {
            return $this->redirectToRoute('current_user_profile');
        }

        $user = $this
        ->getDoctrine()
        ->getRepository(User::class)
        ->find($id);

        if (empty($user)) {
            throw $this->createNotFoundException('User #' . $id . " not found");
        }

        return $this->render('user/profile.html.twig', ['user'=> $user]);
        // return $this->render('user/index.html.twig', [
        //     'controller_name' => 'UserController',
        // ]);
    }
        /**
     * @Route("/users/me", name="current_user_profile")
     * @param Request $req
     * @return Response
     */
    public function me(Request $req) {

        /** @var $user User*/
        $user = $this->getUser();
        $user->setAvatar(null); //TODO remove this lane asap

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                /* @var UploadedFile $file */
                $file = $user->getAvatar();
                $ext = $file->guessExtension();
                $basename = 'profile-picture-' . $user->getId();

                $filename = $basename . '.' . $ext;

                $file->move($this->getParameter('user_upload_folder'), $filename);
                $user->setAvatar($filename);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/users/new", name="create_profile")
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $response = new Response();

        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        // gérer les données recues
        // valider les données
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

                $this->addFlash('success', 'Votre compte à bien été enregistré.');

                //  MESSAGE FLASHE
                $this->addFlash(
                    'notice',
                    'Bienvenue dans la communauté des pixelmarkets!'
                );        

                return $this->redirectToRoute('app_login');
        }


        return $this->render('user/profile-form.html.twig', [
            'user_form' => $form->createView()
        ], $response);
    }
}

