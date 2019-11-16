<?php


namespace App\Controller;

use App\Entity\Post;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Controller extends AbstractController
{
    /**
     * @Route("/home/{cat}", name="home", methods={"GET"}, defaults={"page": 1, "cat": null}))
     * @param Request $request, 
     * @param AuthenticationUtils $authenticationUtils
     * @return
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request, $page, $cat): Response  {

           // Gestion de la pagination
           $page = (int) $request->query->get('p');

           if (!isset($page)) {
               $page = 1;
           }
   
           $page = max(1, $page);
           $start = ($page - 1) * 12;

            if ($cat == null) {
                $totalPosts = $this
                    ->getDoctrine()
                    ->getRepository(Post::class)
                    ->countForHomepage();
            }else{
                $totalPosts = $this
                    ->getDoctrine()
                    ->getRepository(Post::class)
                    ->countByCat($cat);
           }
   
           $max = ceil($totalPosts / 12);
           $totalPages = intval(ceil($max));
           $isLastPage = $page === $totalPages;
   
   
           // get the login error if there is one
           $error = $authenticationUtils->getLastAuthenticationError();
           // last username entered by the user
           $lastUsername = $authenticationUtils->getLastUsername();
   
           // On récupère tous les Posts publics
           $posts = $this
               ->getDoctrine()
               ->getRepository(Post::class)
               ->findHomepage($cat, $start);

            $response = new Response();
            if (empty($posts)) {
                $response->setStatusCode(404);
                return $response;
            }
               
            if ($request->isXmlHttpRequest()) {
            /**
             * @see https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
             */
            $response->headers->set('X-PixelMarket-Is-Last-Page', $isLastPage ? '1' : '0' );

            // cette reponse s'enverra dans le js
                return $this->render('post/_list_posts.html.twig', [
                    'posts' => $posts,
                    'cat' => $cat
                ], $response);
            }
   
           // On envoie les posts dans la vue
           return $this->render('homepage.html.twig', [
                'search' => null,
                'posts' => $posts,
                'last_username' => $lastUsername,
                'error' => $error,
                'cat' => $cat,
                'pagination' => [
                    'current' => $page,
                    'max' => $totalPages, // fake limit for now
               ]
           ]);
    }

    /**
     * @Route("/resultats", name="search_results", methods={"POST", "GET"})
     * @param Request $request, 
     */
    public function resultsSearch(Request $request)
    {
        $response = new Response();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $search = $form->getData()['search'];
            
            $results = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findPost($search);

            return $this->render('search.html.twig', [
                'search_form' => $form->createView(), 
                'posts' => $results
            ]);
        }

        return $this->render('search.html.twig', [
            'search_form' => $form->createView(),
            'posts' => null
        ]);
    }
}