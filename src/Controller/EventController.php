<?php

namespace App\Controller;

use App\Services\BitcoinService;
use App\Services\RandomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as SWG;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/api")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/send", name="api_send", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Return ...",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="value", type="string", example="123,456")
     *     )
     * )
     * @SWG\Tag(name="Bitcoin")
     */
    public function send(): Response
    {
        return $this->json('test');
    }

}
