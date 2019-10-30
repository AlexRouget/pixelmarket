<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Form\UserType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{id<\d+>}", name="user_profile")
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
    }
        /**
     * @Route("/me", name="current_user_profile")
     * @param Request $req
     * @return Response
     */
    public function me(Request $req, PostRepository $postRepository) {

        /** @var $user User*/
        $user = $this->getUser();

        // EN FAIRE DES RECHERCHES PAR CAT / FAVORIS... 
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'posts' => $postRepository->findPostList(0, 4)
        ]);
    }
}

