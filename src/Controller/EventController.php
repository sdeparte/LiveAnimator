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
    const MUSIC_NO_SOUND_EVENT_TYPE = 'music_no_sound';
    const WORKOUT_EVENT_TYPE = 'workout';
    const WORKOUT_PAUSE_EVENT_TYPE = 'workout_pause';

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
     *             "albumImg": "url(https://www.formica.com/fr-fr/-/media/formica/emea/products/swatch-images/f2253/f2253-swatch.jpg)",
     *             "noSound": false
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="author", required=true, description="Author of the current music", type="string"),
     *              @SWG\Property(property="song", required=true, description="Title of the current music ('background' css property)", type="string"),
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

    /**
     * @Route("/music/noSound", name="api_music_noSound", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "noSound": true
     *         },
     *         @SWG\Schema (
     *              type="object",
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
    public function musicPause(HubInterface $hub, Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::MUSIC_NO_SOUND_EVENT_TYPE,
            'noSound' => $body['noSound'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/workout", name="api_workout", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "trainingName": "Pectoraux",
     *             "exerciceName": "Développé couché",
     *             "exerciceNumber": 2,
     *             "series": "2/4",
     *             "repetitions": 10
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="visible", required=true, description="Called on done", type="boolean"),
     *              @SWG\Property(property="trainingName", description="Name of the current training", type="string"),
     *              @SWG\Property(property="exerciceName", description="Name of the current exercice", type="string"),
     *              @SWG\Property(property="exerciceNumber", description="Exercice position in the training", type="integer"),
     *              @SWG\Property(property="series", description="Series position / Series total count", type="string", pattern="#^[0-9]+/[0-9]+$#"),
     *              @SWG\Property(property="repetitions", description="Number of repetitions", type="integer"),
     *              @SWG\Property(property="duration", description="Duration of the series or the rest pause (in seconds)", type="integer"),
     *              @SWG\Property(property="isRest", description="If duration is set, it is a Rest Time or not ?", type="boolean")
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
    public function workout(HubInterface $hub, Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::WORKOUT_EVENT_TYPE,
            'visible' => $body['visible'],
            'trainingName' => $body['trainingName'] ?? null,
            'exerciceName' => $body['exerciceName'] ?? null,
            'exerciceNumber' => $body['exerciceNumber'] ?? null,
            'series' => $body['series'] ?? null,
            'repetitions' => $body['repetitions'] ?? null,
            'duration' => $body['duration'] ?? null,
            'isRest' => $body['isRest'] ?? null,
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }

    /**
     * @Route("/workout/pause", name="api_workout_pause", methods={"POST"})
     *
     * @SWG\RequestBody(
     *     required=true,
     *     @SWG\JsonContent(
     *         example={
     *             "pause": true
     *         },
     *         @SWG\Schema (
     *              type="object",
     *              @SWG\Property(property="pause", required=true, description="Pause Workout timer", type="boolean")
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
    public function workoutPause(HubInterface $hub, Request $request): Response
    {
        $body = json_decode($request->getContent(), true);

        $params = [
            'type' => self::WORKOUT_PAUSE_EVENT_TYPE,
            'pause' => $body['pause'],
        ];

        $result = $hub->publish(new Update(self::MERCURE_TOPIC, json_encode($params)));

        return $this->json(['uuid' => $result]);
    }
}
