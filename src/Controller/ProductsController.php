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

        return new JsonResponse($products);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/products/{id}", name="products_show", requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function show(int $id)
    {
        $product = $this->getDoctrine()
            ->getRepository('App:Product')
            ->find($id);

        if (empty($product)) {
            return new JsonResponse(['message' => 'Product not found.'], 404);
        }

        return new JsonResponse($product);
    }
}
