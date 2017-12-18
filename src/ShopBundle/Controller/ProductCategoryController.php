<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\ProductCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Productcategory controller.
 *
 * @Route("category")
 */
class ProductCategoryController extends Controller
{
    /**
     * Lists all productCategory entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productCategories = $em->getRepository('ShopBundle:ProductCategory')->findAll();

        return $this->render('@Shop/productcategory/index.html.twig', array(
            'productCategories' => $productCategories,
        ));
    }

    /**
     * Creates a new productCategory entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $productCategory = new Productcategory();
        $form = $this->createForm('ShopBundle\Form\ProductCategoryType', $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productCategory);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $productCategory->getId()));
        }

        return $this->render('@Shop/productcategory/new.html.twig', array(
            'productCategory' => $productCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a productCategory entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(ProductCategory $productCategory)
    {
        $deleteForm = $this->createDeleteForm($productCategory);

        return $this->render('@Shop/productcategory/show.html.twig', array(
            'productCategory' => $productCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

	/**
	 * Displays a form to edit an existing productCategory entity.
	 *
	 * @Route("/{id}/edit", name="category_edit")
	 * @Method({"GET", "POST"})
	 * @param Request $request
	 * @param ProductCategory $productCategory
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function editAction(Request $request, ProductCategory $productCategory)
    {
        $deleteForm = $this->createDeleteForm($productCategory);
        $editForm = $this->createForm('ShopBundle\Form\ProductCategoryType', $productCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_edit', array('id' => $productCategory->getId()));
        }

        return $this->render('@Shop/productcategory/edit.html.twig', array(
            'productCategory' => $productCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

	/**
	 * Deletes a productCategory entity.
	 *
	 * @Route("/{id}", name="category_delete")
	 * @Method("DELETE")
	 * @param Request $request
	 * @param ProductCategory $productCategory
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function deleteAction(Request $request, ProductCategory $productCategory)
    {
        $form = $this->createDeleteForm($productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($productCategory);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a productCategory entity.
     *
     * @param ProductCategory $productCategory The productCategory entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(ProductCategory $productCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $productCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
