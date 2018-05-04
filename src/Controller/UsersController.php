<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsersController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @Route("/users", name="users_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()
            ->getRepository('App:User')
            ->findAll();

        $data = $this->get('serializer')->serialize($users, 'json', ['groups' => ['list']]);

        return JsonResponse::fromJsonString($data);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/users/{id}", name="users_show")
     * @Method("GET")
     */
    public function showAction(int $id)
    {
        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->find($id);

        if (empty($user)) {
            return new JsonResponse(['message' => 'Not found.'], 404);
        }

        $data = $this->get('serializer')->serialize($user, 'json');

        return JsonResponse::fromJsonString($data);
    }
}
