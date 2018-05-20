<?php

namespace App\EventListener;

use App\Exception\ApiProblem;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationExceptionListener
{
    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $message = 'Bad credentials, please verify that your username/password are correctly set';

        $apiProblem = $this->createApiProblem(401, $message);

        $event->setResponse($apiProblem);
    }

    /**
     * @param $statusCode
     * @param $message
     * @return JsonResponse
     */
    private function createApiProblem($statusCode, $message)
    {
        $apiProblem = new ApiProblem($statusCode);
        $apiProblem->set('detail', $message);

        $response = new JsonResponse($apiProblem->toArray(), $statusCode);
        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }
}
