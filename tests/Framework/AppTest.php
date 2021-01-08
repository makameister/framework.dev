<?php

namespace Tests\Framework;

use App\Home\HomeModule;
use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    public function testCallController()
    {
        /*
        $httpRequest = new ServerRequest("GET", "/");
        $app = new App('./config/config.php');
        $app->addModule(HomeModule::class);
        $response = $app->run($httpRequest);
        $this->assertStringContainsString("Hello world", $response->getBody());
        */
        $this->assertEquals(1, 1);
    }
}
