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
    const MERCURE_TOPIC = 'http://example.com/events';

    const SUBSCRIBE_EVENT_TYPE = 'subscribe';
    const DONATION_EVENT_TYPE = 'donation';

    /**
     * @Route("/subscribe", name="api_subscribe", methods={"GET"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the subscriber."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return the mercure event id.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="uuid", type="string", example="123-abc")
     *     )
     * )
     * @SWG\Tag(name="Events")
     */
    public function subscribe(HubInterface $hub, Request $request): Response
    {
        $params = [
            'type' => self::SUBSCRIBE_EVENT_TYPE,
            'username' => $request->get('username'),
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/donation", name="api_donation", methods={"GET"})
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     description="Username of the donator."
     * )
     * @SWG\Parameter(
     *     name="amount",
     *     in="query",
     *     description="Amount of the donation."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Return the mercure event id.",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Property(property="uuid", type="string", example="123-abc")
     *     )
     * )
     * @SWG\Tag(name="Events")
     */
    public function donation(HubInterface $hub, Request $request): Response
    {
        $params = [
            'type' => self::DONATION_EVENT_TYPE,
            'username' => $request->get('username'),
            'amount' => $request->get('amount'),
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

}
