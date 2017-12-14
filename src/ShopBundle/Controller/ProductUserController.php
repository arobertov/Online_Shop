<?php

namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductUsers;
use ShopBundle\Form\ProductUsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductUserController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("new_products",name="new_products_users")
     */
    public function indexAction(Request $request)
    {
        $productUsers = new ProductUsers();
        $product = new Product();
        $form = $this->createForm(ProductUsersType::class,$productUsers);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $productUsers->getProduct()->setDateCreated(new \DateTime('now'));
            $em->persist($productUsers);
            $em->flush();
            return $this->redirectToRoute('product_list');
        }
        return $this->render('@Shop/product_users/new_product.html.twig', array(
            'form'=>$form->createView()
        ));
    }
}
