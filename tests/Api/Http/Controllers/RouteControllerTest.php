<?php

namespace Tests;

use GetCandy\Api\Routes\Models\Route;

/**
 * @group api
 * @group new
 * @group controllers
 */
class RouteControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get($this->url('routes'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [['id', 'default', 'locale', 'slug', 'type']],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testUnauthorisedIndex()
    {
        $response = $this->get($this->url('routes'), [
            'Authorization' => 'Bearer foo.bar.bing',
            'Accept' => 'application/json'
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testShow()
    {
        // Get a channel
        $id = Route::first()->encodedId();

        $response = $this->get($this->url('routes/' . $id), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => ['id', 'default', 'locale', 'slug', 'type']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testMissingShow()
    {
        $response = $this->get($this->url('routes/123456'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $this->assertHasErrorFormat($response);

        $this->assertEquals(404, $response->status());
    }
}
