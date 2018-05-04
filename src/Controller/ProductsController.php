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
    public function listAction()
    {
        $products = $this->getDoctrine()
            ->getRepository('App:Product')
            ->findAll();

        $data = $this->get('serializer')->serialize($products, 'json', ['groups' => ['list']]);

        return JsonResponse::fromJsonString($data);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/products/{id}", name="products_show")
     * @Method("GET")
     */
    public function showAction(int $id)
    {
        $product = $this->getDoctrine()
            ->getRepository('App:Product')
            ->find($id);

        if (empty($product)) {
            return new JsonResponse(['message' => 'Not found.'], 404);
        }

        $data = $this->get('serializer')->serialize($product, 'json', ['groups' => ['product']]);

        return JsonResponse::fromJsonString($data);
    }
}
