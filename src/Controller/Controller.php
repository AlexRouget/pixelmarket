<?php


namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Controller extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response  {

           // Gestion de la pagination
           $page = (int) $request->query->get('p');
           $categories = $request->query->get('cat');

           if (!isset($page)) {
               $page = 1;
           }
   
           $page = max(1, $page);
           $start = ($page - 1) * 5;
           $totalPosts = $this
               ->getDoctrine()
               ->getRepository(Post::class)
               ->countForHomepage();
   
           $max = ceil($totalPosts / 5);
   
           // get the login error if there is one
           $error = $authenticationUtils->getLastAuthenticationError();
           // last username entered by the user
           $lastUsername = $authenticationUtils->getLastUsername();
   
           // On récupère tous les Posts publics
           $posts = $this
               ->getDoctrine()
               ->getRepository(Post::class)
               ->findHomepage($start, $max, true, $categories);
   
           // On envoie les posts dans la vue
           return $this->render('homepage.html.twig', [
               'posts' => $posts,
               'last_username' => $lastUsername,
               'error' => $error,
               'pagination' => [
                   'current' => $page,
                   'max' => $max, // fake limit for now
               ]
           ]);
    }
}