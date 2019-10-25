<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users/{id}", name="user_profile")
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

}
