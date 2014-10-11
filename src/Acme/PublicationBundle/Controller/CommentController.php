<?php

namespace Acme\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\PublicationBundle\Entity\Comment;
use Acme\PublicationBundle\Entity\Publication;
use Acme\PublicationBundle\Form\CommentType;

/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{

    /**
     * Creates a new Comment entity.
     *
     * @Route("/{publication_id}", name="comment_create")
     * @Method("POST")
     * @Template("AcmePublicationBundle:Publication:show.html.twig")
     */
    public function createAction($publication_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AcmePublicationBundle:Publication')->find($publication_id);

        if (!$publication) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }

        $comment = new Comment();
        $form = $this->createCreateCommentForm($comment, $publication);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $comment->setPublication($publication);
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('publication_show', array('id' => $publication->getId())));
        }

        $entity = $publication;

        return array(
            'entity' => $entity,
            'comment_form' => $form->createView(),
        );
    }

    /**
     * Deletes a Comment entity.
     *
     * @Route("/{id}", name="comment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmePublicationBundle:Comment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comment'));
    }

    /**
     * Creates a form to delete a Comment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Creates a form to create a Comment entity.
     *
     * @param Comment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateCommentForm(Comment $entity, Publication $publication)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('comment_create', array('publication_id' => $publication->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
}
