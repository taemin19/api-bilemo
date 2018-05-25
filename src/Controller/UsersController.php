<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\ApiProblem;
use App\Exception\ApiProblemException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $client = $this->getUser();

        $users = $this->getDoctrine()
            ->getRepository('App:User')
            ->findBy(array('client' => $client));

        if (empty($users)) {
            throw $this->createNotFoundException('No users created.');
        }

        return new JsonResponse($users, 200, ['Content-Type' => 'application/hal+json']);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @Route("/users/{id}", name="users_show")
     * @Method("GET")
     */
    public function show($id)
    {
        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->find($id);

        if (empty($user)) {
            throw $this->createNotFoundException(sprintf('No user found with id "%s"', $id));
        }

        if ($user->getClient() != $this->getUser() ) {
            throw $this->createAccessDeniedException();
        }

        return new JsonResponse($user, 200, ['Content-Type' => 'application/hal+json']);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @Route("/users/{id}", name="users_delete")
     * @Method("DELETE")
     */
    public function delete($id)
    {
        $user =$this->getDoctrine()
            ->getRepository('App:User')
            ->find($id);

        if (empty($user)) {
            throw $this->createNotFoundException(sprintf('No user found with id "%s"', $id));
        }

        if ($user->getClient() != $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     *
     * @Route("/users", name="users_new")
     * @Method("POST")
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }

        $user = new User();
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);
        $user->setClient($this->getUser());

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $message = [];

            foreach ($errors as $error) {
                $message[][$error->getPropertyPath()] = $error->getMessage();
            }

            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_VALIDATION_ERROR);
            $apiProblem->set('invalid-params', $message);

            throw new ApiProblemException($apiProblem);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $userUrl = $this->generateUrl(
            'users_show',
            ['id' => $user->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($user, 201, ['Location' => $userUrl, 'Content-Type' => 'application/hal+json']);
    }
}
