<?php


namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Form\UserType;
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
        $usersCount = $userRepository->findAll();
        $postsCount = $postRepository->findAll();

        $CountNewPosts = $this
        ->getDoctrine()
        ->getRepository(Post::class)
        ->countByChecked();

        $CountNewUsers = $this
        ->getDoctrine()
        ->getRepository(User::class)
        ->countByChecked();

        return $this->render('admin/admin.html.twig', [
            'posts' => $postRepository->findPostList(null, 0, 4, false, false),
            'posts_unchecked' => $postRepository->findPostList(null, 0, 4, false, true),
            'count_posts' =>  count($postsCount),
            'count_new_posts' => $CountNewPosts,
            'users' => $userRepository->findUserList(0, 8, false),
            'users_unchecked' => $userRepository->findBy(['checked' => false], ['createdAt' => 'DESC'], 8),
            'count_users' => count($usersCount),
            'count_new_users' => $CountNewUsers,
            'mainNavHome'=>true,
            'title'=>'Back Office',
        ]);
    }

    /**
     * @Route("/annonces/{onlyUnchecked}", name="admin_posts")
     */
    public function posts(PostRepository $postRepository, $onlyUnchecked): Response
    {
        $posts = $postRepository->findPostList(null, 0, null, false, $onlyUnchecked);

        if ($onlyUnchecked) {
            $title = " LES NOUVELLES ANNONCES ";
            $CountNewPosts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->countByChecked();
        } else {
            $title = "TOUTES LES ANNONCES";
            $CountNewPosts = count($posts);
        }

        return $this->render('admin/posts.html.twig', [
            'posts' => $posts,
            'count_new_posts' => $CountNewPosts,
            'title_posts' => $title,
            ]);
    }

    /**
     * @Route("/utilisateurs/{onlyUnchecked}", name="admin_users")
     */
    public function users(UserRepository $userRepository, $onlyUnchecked): Response
    {
        $users = $userRepository->findUserList( 0, null, $onlyUnchecked);

        if ($onlyUnchecked) {
            $title = "LES NOUVEAUX UTILISATEURS";
            $CountNewUsers = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->countByChecked();
        } else {
            $title = "TOUTES LES UTILISATEURS";
            $CountNewUsers = count($users);
        }

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'count_new_users' => $CountNewUsers,
            'title_users' => $title,
            ]);
    }

    /**
     * @Route("/utilisateurs/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $req
     * @return Response
     */
    public function userEdit(Request $req, PostRepository $postRepository, UserRepository $userRepository, FileUploader $fileUploader, $id): Response {

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
            return $this->redirectToRoute('user_show');
            sleep(5);
        }

        return $this->render('admin/edit_users.html.twig', [
            'user' => $user,
            'user_form' => $form->createView(),
            'title' => 'profil ' .$id ,
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

        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }
        
        return $user;
    }
}