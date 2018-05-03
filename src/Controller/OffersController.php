<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class OffersController extends Controller
{
    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/products/{id}/offers", name="offers_list")
     * @Method("GET")
     */
    public function listAction(int $id)
    {
        $product = $this->getDoctrine()
            ->getRepository('App:Product')
            ->find($id);

        if (empty($product)) {
            return new JsonResponse(['message' => 'Not found.'], 404);
        }

        $offers = $product->getOffers();

        $data = $this->get('serializer')->serialize($offers, 'json', ['groups' => ['offer']]);

        return JsonResponse::fromJsonString($data);
    }
}
