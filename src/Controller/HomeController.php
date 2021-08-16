<?php

namespace App\Controller;

use App\Services\RandomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function index(Authorization $authorization, Request $request): Response
    {
        $response = $this->render('home.html.twig');

        $response->headers->setCookie(
            $authorization->createCookie($request,  [EventController::MERCURE_TOPIC])
        );

        return $response;
    }
}
