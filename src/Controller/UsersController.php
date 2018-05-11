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
    public function list()
    {
        $users = $this->getDoctrine()
            ->getRepository('App:User')
            ->findAll();

        return new JsonResponse($users);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/users/{id}", name="users_show", requirements={"id"="\d+"})
     * @Method("GET")
     */
    public function show(int $id)
    {
        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->find($id);

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found.'], 404);
        }

        return new JsonResponse($user);
    }

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/users/{id}", name="users_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function delete(int $id)
    {
        $user =$this->getDoctrine()
            ->getRepository('App:User')
            ->find($id);

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found.'], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, 204);
    }
}
