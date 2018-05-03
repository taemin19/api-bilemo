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
        $users = $this->getDoctrine()
            ->getRepository('App:Product')
            ->findAll();

        $data = $this->get('serializer')->serialize($users, 'json', ['groups' => ['list']]);

        return JsonResponse::fromJsonString($data);
    }
}
