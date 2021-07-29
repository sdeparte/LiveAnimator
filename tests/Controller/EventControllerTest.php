<?php

namespace App\Tests\Controller;

use App\Controller\EventController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventControllerTest extends KernelTestCase
{
    /** @var ApiController */
    private $apiController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootKernel();
        $this->apiController = new EventController();
        $this->apiController->setContainer(self::$container);

    }
}
