<?php

namespace App\Controller;

use App\Services\BitcoinService;
use App\Services\RandomService;
use Nelmio\ApiDocBundle\Annotation\Security;
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

    const FOLLOW_EVENT_TYPE = 'follow';
    const SUBSCRIBE_EVENT_TYPE = 'subscribe';
    const DONATION_EVENT_TYPE = 'donation';
    const RAID_EVENT_TYPE = 'raid';
    const MUSIC_EVENT_TYPE = 'music';

    /**
     * @Route("/follow", name="api_follow", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "username": "sdeparte"
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="username", required=true, description="Username of the follower", type="string")
     *         )
     *     )
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
     * @Security(name="Bearer")
     */
    public function follow(HubInterface $hub, Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::FOLLOW_EVENT_TYPE,
            'username' => $body['username'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/subscribe", name="api_subscribe", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "username": "sdeparte",
     *             "isPrime": false,
     *             "isGift": true,
     *             "recipient": "anotherUser"
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="username", required=true, description="Username of the subscriber", type="string"),
     *              @SWG\Property(property="isPrime", required=true, description="Subscription type is 'Prime'", type="boolean"),
     *              @SWG\Property(property="isGift", required=true, description="Subscription type is a gift", type="boolean"),
     *              @SWG\Property(property="recipient", description="Is a gift for ?", type="string")
     *         )
     *     )
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
     * @Security(name="Bearer")
     */
    public function subscribe(HubInterface $hub, Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::SUBSCRIBE_EVENT_TYPE,
            'username' => $body['username'],
            'isPrime' => $body['isPrime'],
            'isGift' => $body['isGift'],
            'recipient' => $body['recipient'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/donation", name="api_donation", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "username": "sdeparte",
     *             "amount": "50 coins"
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="username", required=true, description="Username of the donator", type="string"),
     *              @SWG\Property(property="amount", required=true, description="Amount of the donation (with currency)", type="string")
     *         )
     *     )
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
     * @Security(name="Bearer")
     */
    public function donation(HubInterface $hub, Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::DONATION_EVENT_TYPE,
            'username' => $body['username'],
            'amount' => $body['amount'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/raid", name="api_raid", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "username": "sdeparte",
     *             "viewers": 50
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="username", required=true, description="Username of the raid initiator", type="string"),
     *              @SWG\Property(property="viewers", required=true, description="Count of viewers in the raid", type="integer")
     *         )
     *     )
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
     * @Security(name="Bearer")
     */
    public function raid(HubInterface $hub, Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::RAID_EVENT_TYPE,
            'username' => $body['username'],
            'viewers' => $body['viewers'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/music", name="api_music", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "author": "Sylvain D",
     *             "song": "The silence",
     *             "albumImg": "https://www.formica.com/fr-fr/-/media/formica/emea/products/swatch-images/f2253/f2253-swatch.jpg",
     *             "noSound": false
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="author", required=true, description="Author of the current music", type="string"),
     *              @SWG\Property(property="song", required=true, description="Title of the current music", type="string"),
     *              @SWG\Property(property="albumImg", required=true, description="Album image of the current music", type="string"),
     *              @SWG\Property(property="noSound", required=true, description="Hide/Show sound bars", type="boolean")
     *         )
     *     )
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
     * @Security(name="Bearer")
     */
    public function music(HubInterface $hub, Request $request): Response
    {
	$body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::MUSIC_EVENT_TYPE,
            'albumImg' => $body['albumImg'],
            'author' => $body['author'],
	    'song' => $body['song'],
	    'noSound' => $body['noSound'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }
}
