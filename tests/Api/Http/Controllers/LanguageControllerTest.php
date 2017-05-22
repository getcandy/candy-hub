<?php

namespace GetCandy\Api\Tests;

use GetCandy\Api\Models\Language;

/**
 * @group controllers
 * @group api
 */
class LanguageControllerTest extends TestCase
{

    protected $baseStructure = [
        'id',
        'name',
        'code'
    ];

    public function testIndex()
    {
        $response = $this->get($this->url('languages'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [$this->baseStructure],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

    /**
     * @group fail
     */
    public function testUnauthorisedIndex()
    {
        $response = $this->get($this->url('languages'), [
            'Authorization' => 'Bearer foo.bar.bing',
            'Accept' => 'application/json'
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testShow()
    {
        // Get a channel
        $id = Language::first()->encodedId();

        $response = $this->get($this->url('languages/' . $id), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => $this->baseStructure
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testMissingShow()
    {
        $response = $this->get($this->url('languages/123456'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $this->assertHasErrorFormat($response);

        $this->assertEquals(404, $response->status());
    }

    public function testStore()
    {
        $response = $this->post(
            $this->url('languages'),
            [
                'name' =>  "Spanish",
                'code' =>  "es",
                'default' =>  false
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'data' => $this->baseStructure
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testInvalidStore()
    {
        $response = $this->post(
            $this->url('languages'),
            [],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'name', 'code'
        ]);

        $this->assertEquals(422, $response->status());
    }

    public function testStoreUniqueCode()
    {
        $response = $this->post(
            $this->url('languages'),
            [
                'code' => 'en',
                'name' => 'English'
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'code'
        ]);

        $this->assertEquals(422, $response->status());
    }

    public function testUpdate()
    {
        $id = Language::first()->encodedId();
        $response = $this->put(
            $this->url('languages/' . $id),
            [
                'name' => 'Español',
                'code' => 'es',
                'default' => true
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );
        $this->assertEquals(200, $response->status());
    }

    public function testUpdateUniqueCode()
    {
        \GetCandy\Api\Models\Language::create([
            'name' => 'Foo',
            'code' => 'foo'
        ]);

        $id = Language::first()->encodedId();
        $response = $this->put(
            $this->url('languages/' . $id),
            [
                'name' => 'Bar',
                'code' => 'foo'
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'code'
        ]);

        $this->assertEquals(422, $response->status());
    }

    public function testMissingUpdate()
    {
        $response = $this->put(
            $this->url('languages/123123'),
            [
                'name' => 'Español',
                'code' => 'es',
                'default' => true
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $this->assertEquals(404, $response->status());
    }

    public function testDestroy()
    {
        $currency = Language::create([
            'name' =>  "Spanish",
            'code' =>  "es",
            'default' =>  false
        ]);

        $response = $this->delete(
            $this->url('languages/' . $currency->encodedId()),
            [],
            ['Authorization' => 'Bearer ' . $this->accessToken()]
        );

        $this->assertEquals(204, $response->status());
    }

    public function testCannotDestroyLastChannel()
    {
        $id = Language::first()->encodedId();
        $response = $this->delete(
            $this->url('languages/' . $id),
            [],
            ['Authorization' => 'Bearer ' . $this->accessToken()]
        );
        $this->assertEquals(422, $response->status());
    }
}
