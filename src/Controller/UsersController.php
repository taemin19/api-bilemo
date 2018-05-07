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
     * @Route("/users/{id}", name="users_show", requirements={"id"="\d+"})
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

    /**
     * @param int $id
     * @return JsonResponse
     *
     * @Route("/users/{id}", name="users_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(int $id)
    {
        $user =$this->getDoctrine()
            ->getRepository('App:User')
            ->find($id);

        // Idempotent: return a 204 in all cases
        if ($user) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($user);
            $em->flush();
        }

        return new JsonResponse(null, 204);
    }
}
