<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
            throw $this->createNotFoundException(sprintf('No user found with id "%s"', $id));
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
            throw $this->createNotFoundException(sprintf('No user found with id "%s"', $id));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * @return JsonResponse
     *
     * @Route("/users", name="users_new")
     * @Method("POST")
     */
    public function new(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $userUrl = $this->generateUrl(
            'users_show',
            ['id' => $user->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($data, 201, ['Location' => $userUrl]);
    }
}
