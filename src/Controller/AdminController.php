<?php


namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin", methods={"GET", "POST"})
     */
    public function admin(PostRepository $postRepository, UserRepository $userRepository): Response
    {
        return $this->render('admin/admin.html.twig', [
            'posts' => $postRepository->findAll(),
            'users' => $userRepository->findAll(),
            'mainNavHome'=>true,
            'title'=>'Back Office',
        ]);
    }

    /**
     * @Route("users/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $req
     * @return Response
     */
    public function userEdit(Request $req, PostRepository $postRepository, UserRepository $userRepository, FileUploader $fileUploader): Response {

        $user = $this->findOr404($id);
        
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $id = $this->getUser()->getId();
        $avatar = $user->getAvatar();

        if (!is_null($avatar)) {
            $user->setAvatar($avatar);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);

        // gérer les données recues
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('avatar')->getData();

            if ($file !== null) {
                $fileName = $fileUploader->upload($file);
                $user->setAvatar($fileName);
            } else {
                $user->setAvatar($avatar);
            }

            $date = new \Datetime();
            $user->setUpdatedAt($date);

            $em->persist($user);
            $em->flush();

            //  MESSAGE FLASHE     
            $this->addFlash('success', 'Les modifications ont bien été prise en compte');
            return $this->redirectToRoute('current_user_profile');
            sleep(5);
        }

        return $this->render('user/_edit-form.html.twig', [
            'user' => $user,
            'user_form' => $form->createView(),
            'title' => 'profil'.$id ,
        ]);
    }

    /**
     * @Route("users/{id<\d+>}", name="user_show")
     */
    public function userShow($id, SessionInterface $session)
    {
        $this->session = $session;

        if (!$this->session) {            
            if(intval($id) === $this->getUser()->getId()) {
                return $this->redirectToRoute('current_user_profile');
            }
        }

        $user = $this->findOr404($id);

        $username = $user->getUsername();

        if (empty($user)) {
            throw $this->createNotFoundException('Utilisateur #' . $id . " introuvable");
        }

        return $this->render('user/profile.html.twig', ['user'=> $user, 'title' => 'Profil de ' . $username,]);
    }

    private function findOr404($id) {

        $post = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (empty($post)) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }
        
        return $user;
    }
}