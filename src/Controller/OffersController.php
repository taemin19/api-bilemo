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

    /**
     * @param int $id
     * @param int $offer_id
     * @return JsonResponse
     *
     * @Route("/products/{id}/offers/{offer_id}", name="offers_show")
     * @Method("GET")
     */
    public function showAction(int $id, int $offer_id)
    {
        $offer = $this->getDoctrine()
            ->getRepository('App:Offer')
            ->find($offer_id);

        if (empty($offer) || !($offer->getProduct()->getId() === $id)) {
            return new JsonResponse(['message' => 'Not found.'], 404);
        }

        $data = $this->get('serializer')->serialize($offer, 'json', ['groups' => ['offer']]);

        return JsonResponse::fromJsonString($data);
    }
}
