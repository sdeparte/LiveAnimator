<?php

namespace App\Controller;

use App\Services\RandomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Routing\Annotation\Route;

class OverlaysController extends AbstractController
{
    /**
     * @Route("/overlays/muscu", name="overlay_muscu", methods={"GET"})
     */
    public function overlayMuscu(Authorization $authorization, Request $request): Response
    {
        $response = $this->render('overlay_muscu.html.twig');

        $response->headers->setCookie(
            $authorization->createCookie($request,  [EventController::MERCURE_TOPIC])
        );

        return $response;
    }
}

