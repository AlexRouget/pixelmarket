<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/_posts.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request){
        $response = new Response();

        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
                $post = $form->getData();
                $post->setAuthor($this->getUser());

                /* @var UploadedFile $file */
                $file = $post->getAttachment();
                if (!empty($file)) {
                    $basename = 'post-attachment-' . md5(uniqid());
                    // c'est une identifiant basé sur la date en microseconde. c'est n'est pas utilisable en secu, de plus on peut avoir la même chaîne de caractère
                    $ext = $file->guessExtension();
                    $filename = $basename . '.' . $ext;

                    $file->move($this->getParameter('user_upload_folder'), $filename);
                    $post->setAttachment($filename);
                }
            // on dit à Doctrine de "s'occuper" de ce Post
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);

            // finalement, on dit au manager d'envoyer le post en BDD
            $manager->flush();

            $this->addFlash('success', 'Ton post à été ajouté!');

            return $this->redirectToRoute('current_user_profile');
        }


        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/single.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('success', 'Ton post à été modifié!');
            
            return $this->redirectToRoute('current_user_profile');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            
            $this->addFlash('success', 'Ton post à été supprimé!');
        }


        return $this->redirectToRoute('current_user_profile');
    }
}
