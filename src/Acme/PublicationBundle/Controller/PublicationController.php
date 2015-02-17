<?php

namespace Acme\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Acme\PublicationBundle\Entity\Publication;
use Acme\PublicationBundle\Entity\Comment;
use Acme\PublicationBundle\Form\PublicationType;
use Acme\PublicationBundle\Form\CommentType;

/**
 * Publication controller.
 */
class PublicationController extends FOSRestController
{

    /**
     * Lists all Publication entities.
     *
     * @ApiDoc(
     *      section="Publications",
     *      resource = true,
     *      description="List publications",
     *      statusCodes={
     *          200="OK",
     *          400="An error occurred, check error message",
     *          403="User is not authorized"
     *      }
     * )
     *
     * @Annotations\View(
     *  templateVar="publications"
     * )
     *
     * @return JsonResponse
     */
    public function getPublicationsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AcmePublicationBundle:Publication')->findAll();

        $em->getFilters()->disable('softdeleteable');
        $deleted = $em->getRepository('AcmePublicationBundle:Publication')->findAllDeleted();

        return array(
            'entities' => $entities,
            'deleted' => $deleted,
        );
    }

    /**
     * Creates a new Publication entity.
     *
     * @ApiDoc(
     *      section="Publications",
     *      resource = true,
     *      description="Create publication",
     *      statusCodes={
     *          200="OK",
     *          400="An error occurred, check error message",
     *          403="User is not authorized"
     *      }
     * )
     *
     * @Annotations\View(
     *  template = "AcmePublicationBundle:Publication:newPublication.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     */
    public function postPublicationAction(Request $request)
    {
        $entity = new Publication();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('publications_get_publication', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Publication entity.
     *
     * @param Publication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Publication $entity)
    {
        $form = $this->createForm(new PublicationType(), $entity, array(
            'action' => $this->generateUrl('publications_post_publication'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Publication entity.
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     */
    public function newPublicationAction()
    {
        $entity = new Publication();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Publication entity.
     *
     * @ApiDoc(
     *      section="Publications",
     *      resource = true,
     *      description="Show publication",
     *      output = "Acme\PublicationBundle\Entity\Publication",
     *      statusCodes={
     *          200="OK",
     *          400="An error occurred, check error message",
     *          403="User is not authorized"
     *      }
     * )
     *
     * @Annotations\View(templateVar="publication")
     *
     */
    public function getPublicationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmePublicationBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $comment = new Comment();
        $commentForm = $this->createCreateCommentForm($comment, $entity);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'comment_form' => $commentForm->createView(),

        );
    }

    /**
     * Displays a form to edit an existing Publication entity.
     *
     * @Annotations\View(
     *  template = "AcmePublicationBundle:Publication:editPublication.html.twig",
     *  templateVar = "form"
     * )
     *
     */
    public function editPublicationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmePublicationBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Publication entity.
    *
    * @param Publication $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Publication $entity)
    {
        $form = $this->createForm(new PublicationType(), $entity, array(
            'action' => $this->generateUrl('publications_put_publication', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Publication entity.
     *
     * @ApiDoc(
     *   section="Publications",
     *   resource = true,
     *   description="Upadate publication",
     *   input = "Acme\PublicationBundle\Form\PublicationType",
     *   statusCodes = {
     *     201 = "Returned when the Category is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmePublicationBundle:Publication:editPublication.html.twig",
     *  templateVar = "form"
     * )
     *
     */
    public function putPublicationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmePublicationBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('publications_get_publication', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Publication entity.
     *
     * @ApiDoc(
     *      section="Publications",
     *      resource = true,
     *      description="Delete publication",
     *      statusCodes={
     *          200="OK",
     *          400="An error occurred, check error message",
     *          403="User is not authorized"
     *      }
     * )
     *
     * @param  Request $request
     * @param  integer $id
     */
    public function deletePublicationAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmePublicationBundle:Publication')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Publication entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('publications_get_publications'));
    }

    /**
     * Creates a form to delete a Publication entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('publications_delete_publication', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => 'Delete',
                'attr' => [
                    'class' => 'btn btn-default'
                    ]
                ]
            )
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
