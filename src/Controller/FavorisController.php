<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class FavorisController extends AbstractController
{
    /**
     * @Route("/{id}/like", name="post_like")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function like(Request $request, $id) {

        
        $post = $this->findOr404($id);
        $user = $this->getUser();
        $user->addLiked($post);

        $this->getDoctrine()->getManager()->flush();

        if ($request->isXmlHttpRequest()) {
            /**
             * @see https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
             */
            return new Response('', 201);
        }

        // Naive redirect to the previous page
        $referer = $request->headers->get('referer');

        if (!$referer) {
            return $this->redirectToRoute('home');
        }

        return $this->redirect($referer);
    }

    /**
     * @Route("/{id}/unlike", name="post_unlike")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function unlike(Request $request, $id) {

        $post = $this->findOr404($id);

        $this->getUser()->removeLiked($post);

        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        if ($request->isXmlHttpRequest()) {
            /**
             * @see https://fr.wikipedia.org/wiki/Liste_des_codes_HTTP
             */
            return new Response(null, 204);
        }
        // Naive redirect to the previous page
        $referer = $request->headers->get('referer');

        if (!$referer) {
            return $this->redirectToRoute('post_single', ['id' => $id]);
        }

        return $this->redirect($referer);
    }


    private function findOr404($id) {

        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (empty($post)) {
            throw $this->createNotFoundException('Post introuvable');
        }
        
        return $post;
    }
}
