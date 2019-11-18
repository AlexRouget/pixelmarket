<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
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
                    //TODO c'est une identifiant basé sur la date en microseconde. c'est n'est pas utilisable en secu, de plus on peut avoir la même chaîne de caractère
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
            'post_form' => $form->createView(),
            'title' => 'Créer une annonce',
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"}, requirements={"id"="[0-9]+"})
     */
    public function show($id): Response
    {

        // On va chercher en BDD le post qui correspond à l'ID
        $post = $this->findOr404($id);

        return $this->render('post/single.html.twig', [
            'post' => $post,
            'title' => 'Annonce #' . $id,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id, FileUploader $fileUploader): Response
    {
        // On va chercher en BDD le post qui correspond à l'ID
        $post = $this->findOr404($id);
        // le user du post
        $userId = $post->getAuthor()->getId();
        //user connected
        $user = $this->getUser()->getId();

        if(($userId != $user) and ($this->getUser()->getRoles()[0] != 'ROLE_ADMIN')){
            $this->addFlash('danger', 'Ce post ne t\'appartiens pas !');   
            return $this->redirectToRoute('current_user_profile');
        }
        
        $file = $post->getAttachment();
        $public = $post->getPublic();
        $published = $post->getPublishedAt();

        // dump($post);die;
        if (!is_null($file)) {
            $post->setAttachment($file);
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var UploadedFile $file */
            $fileForm = $form->get('attachment')->getData();
            $publicForm = $form->get('public')->getData();

            if ($fileForm !== null) {
                $fileName = $fileUploader->upload($fileForm);
                $post->setAttachment($fileName);
            } else {
                $post->setAttachment($file);
            }
            
            $date = new \Datetime();

            if ($publicForm) {
                $post->setPublishedAt($date);
            } else {
                $post->setPublishedAt(null);
            }
            
            //update date
            $post->setUpdatedAt($date);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Ton post à été modifié!');
            
            return $this->redirectToRoute('current_user_profile');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'post_form' => $form->createView(),
            'title' => 'Modifier mon annonce',
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        // On va chercher en BDD le post qui correspond à l'ID
        $post = $this->findOr404($id);

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            
            $this->addFlash('success', 'Ton post à été supprimé!');
            return $this->redirectToRoute('current_user_profile');
        }


        return $this->redirectToRoute('current_user_profile');
    }
    
    private function findOr404($id) {

        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if (empty($post)) {
            throw $this->createNotFoundException('Annonce introuvable');
        }
        
        return $post;
    }
}
