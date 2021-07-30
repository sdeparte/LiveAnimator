<?php

namespace App\Controller;

use App\Services\BitcoinService;
use App\Services\RandomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as SWG;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/api")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/confettis", name="api_confettis", methods={"GET"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the new follower."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return ...",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="value", type="string", example="123,456")
     *     )
     * )
     * @SWG\Tag(name="Events")
     */
    public function confettis(HubInterface $hub, Request $request): Response
    {
        $update = new Update(
            'http://example.com/confettis',
            json_encode(['username' => $request->get('username')])
        );

        $result = $hub->publish($update);

        return $this->json([$result]);
    }

    /**
     * @Route("/coins", name="api_coins", methods={"GET"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the new follower."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return ...",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="value", type="string", example="123,456")
     *     )
     * )
     * @SWG\Tag(name="Events")
     */
    public function coins(HubInterface $hub, Request $request): Response
    {
        $update = new Update(
            'http://example.com/coins',
            json_encode(['username' => $request->get('username')])
        );

        $result = $hub->publish($update);

        return $this->json([$result]);
    }

}
