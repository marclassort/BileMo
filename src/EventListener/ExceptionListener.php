<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());

            switch ($exception->getStatusCode()) {
                case 403:
                    $message = "Accès interdit";
                    break;
                case 404:
                    $message = "Page introuvable";
                    break;
                case 500:
                    $message = "Erreur serveur";
                    break;
                case 400:
                    
                default:
                    $message = "Erreur survenue";
                    break;
            }

            $response->setData([
                'status' => $exception->getStatusCode(),
                'message' => $message
            ]);
            $response->setStatusCode(JsonResponse::HTTP_NOT_FOUND);

            // $response->setContent("Code : " . $exception->getStatusCode() . ",<br>Message d'erreur : " . $message);
        } else {
            $response->setContent("Une erreur inconnue est survenue.");
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}
