<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductsController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @Route("/products", name="products_list")
     * @Method("GET")
     */
    public function list()
    {
        $products = $this->getDoctrine()
            ->getRepository('App:Product')
            ->findAll();

        return new JsonResponse($products, 200, ['Content-Type' => 'application/hal+json']);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @Route("/products/{id}", requirements={"id" = "\d+"}, name="products_show")
     * @Method("GET")
     */
    public function show($id)
    {
        $product = $this->getDoctrine()
            ->getRepository('App:Product')
            ->find($id);

        if (empty($product)) {
            throw $this->createNotFoundException(sprintf('No product found with id "%s"', $id));
        }

        return new JsonResponse($product, 200, ['Content-Type' => 'application/hal+json']);
    }
}
