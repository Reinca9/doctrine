<?php
namespace Tests\App;

use App\Framework\App;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;

class AppTest extends TestCase
{

    public function testIndex()
    {
        $app = new App();
        $request = new ServerRequest('GET', '');
        $response = $app->run($request);
        $this->assertEquals('bonjour', (string)$response->getBody());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRedirectSlash()
    {
        $app = new App();
        $request = new ServerRequest('GET', '/abc/');
        $response = $app->run($request);
        $this->assertContains('/abc', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }
}