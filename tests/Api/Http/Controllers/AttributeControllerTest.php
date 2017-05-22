<?php

namespace GetCandy\Api\Tests;

use GetCandy\Api\Models\Attribute;

/**
 * @group new
 * @group api
 */
class AttributeControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get($this->url('attributes'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [['id', 'name', 'filterable', 'variant', 'searchable']],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

/**
 * @group fail
 * @return [type] [description]
 */
    public function testUnauthorisedIndex()
    {
        $response = $this->get($this->url('attributes'), [
            'Authorization' => 'Bearer foo.bar.bing',
            'Accept' => 'application/json'
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testShow()
    {
        // Get an attribute
        $id = Attribute::first()->encodedId();

        $response = $this->get($this->url('attributes/' . $id), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => ['id', 'name', 'filterable', 'variant', 'searchable']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testMissingShow()
    {
        $response = $this->get($this->url('attributes/123456'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $this->assertHasErrorFormat($response);

        $this->assertEquals(404, $response->status());
    }

    public function testStore()
    {
        $group = \GetCandy\Api\Models\AttributeGroup::first();

        $response = $this->post(
            $this->url('attributes'),
            [
                'name' => 'Neon',
                'group_id' => $group->encodedId()
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'data' => ['id', 'name', 'handle', 'position', 'filterable', 'variant', 'searchable']
        ]);

        $this->assertEquals(200, $response->status());
    }
}
