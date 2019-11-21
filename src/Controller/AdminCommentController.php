<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $comment)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comment->findAll(),
        ]);
    }

    /**
     * Permet à l'admin de modifier un commentaire
     *
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     * 
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function edit(Request $request, Comment $comment)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', "Vous avez bien modifer le commentaire");

            return $this->redirectToRoute('admin_comment_index');
        }

        return $this->render('admin/comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment
        ]);
    }

    /**
     * Permet à l'adminde supprimer un commentaire
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     * @param Comment $comment
     * @return Response
     */
    public function delete(Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $this->addFlash('success',"vous avez bien supprimer le commentaire de {$comment->getAuthor()->getFullName()}");
        
        return $this->redirectToRoute('admin_comment_index');
    } 
    

}
