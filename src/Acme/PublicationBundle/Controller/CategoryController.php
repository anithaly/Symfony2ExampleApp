<?php

namespace Acme\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Acme\PublicationBundle\Entity\Category;
use Acme\PublicationBundle\Form\CategoryType;

/**
 * Category controller.
 */
class CategoryController extends FOSRestController
{

    /**
     * Lists all Category entities.
     *
     * @ApiDoc(
     *      section="Categories",
     *      resource = true,
     *      description="List categorties",
     *      statusCodes={
     *          200="OK",
     *          400="An error occurred, check error message",
     *          403="User is not authorized"
     *      }
     * )
     *
     * @Annotations\View(
     *  templateVar="categories"
     * )
     *
     * @return JsonResponse
     */
    public function getCategoriesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AcmePublicationBundle:Category')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Category entity.
     *
     * @ApiDoc(
     *      section="Categories",
     *      resource = true,
     *      description="Create category",
     *      statusCodes={
     *          200="OK",
     *          400="An error occurred, check error message",
     *          403="User is not authorized"
     *      }
     * )
     *
     * @Annotations\View(
     *  template = "AcmePublicationBundle:Category:newCategory.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     */
    public function postCategoryAction(Request $request)
    {
        $entity = new Category();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('categories_get_category', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Category entity.
     *
     * @param Category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('categories_post_category'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     */
    public function newCategoryAction()
    {
        $entity = new Category();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Category entity.
     *
     * @ApiDoc(
     *      section="Categories",
     *      resource = true,
     *      description="Show category",
     *      output = "Acme\PublicationBundle\Entity\Category",
     *      statusCodes={
     *          200="OK",
     *          400="An error occurred, check error message",
     *          403="User is not authorized"
     *      }
     * )
     *
     * @Annotations\View(templateVar="category")
     *
     * @throws NotFoundHttpException when page not exist
     */
    public function getCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmePublicationBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Annotations\View(
     *  template = "AcmePublicationBundle:Category:editCategory.html.twig",
     *  templateVar = "form"
     * )
     *
     */
    public function editCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmePublicationBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
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
    * Creates a form to edit a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('categories_put_category', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Category entity.
     *
     * @ApiDoc(
     *   section="Categories",
     *   resource = true,
     *   description="Update category",
     *   input = "Acme\PublicationBundle\Form\CategoryType",
     *   statusCodes = {
     *     201 = "Returned when the Category is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AcmePublicationBundle:Category:editCategory.html.twig",
     *  templateVar = "form"
     * )
     *
     */
    public function putCategoryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AcmePublicationBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('categories_get_category', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Category entity.
     *
     * @ApiDoc(
     *      section="Categories",
     *      resource = true,
     *      description="Delete category",
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
    public function deleteCategoryAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AcmePublicationBundle:Category')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Category entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('categories_get_categories'));
    }

    /**
     * Creates a form to delete a Category entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categories_delete_category', array('id' => $id)))
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
}
