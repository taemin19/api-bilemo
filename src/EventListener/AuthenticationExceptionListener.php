<?php

namespace App\EventListener;

use App\Exception\ApiProblem;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationExceptionListener
{
    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $message = 'Bad credentials, please verify that your username/password are correctly set';

        $apiProblem = $this->createApiProblem(400, $message);

        $event->setResponse($apiProblem);
    }

    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $message = 'JWT token is invalid.';

        $apiProblem = $this->createApiProblem(401, $message);
        $apiProblem->headers->set('WWW-Authenticate', 'Bearer');

        $event->setResponse($apiProblem);
    }

    /**
     * @param JWTNotFoundEvent $event
     */
    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $message = 'Authentication required.';

        $apiProblem = $this->createApiProblem(401, $message);
        $apiProblem->headers->set('WWW-Authenticate', 'Bearer');

        $event->setResponse($apiProblem);
    }

    /**
     * @param JWTExpiredEvent $event
     */
    public function onJWTExpired(JWTExpiredEvent $event)
    {
        $message = 'JWT token is expired.';

        $apiProblem = $this->createApiProblem(401, $message);
        $apiProblem->headers->set('WWW-Authenticate', 'Bearer');

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
