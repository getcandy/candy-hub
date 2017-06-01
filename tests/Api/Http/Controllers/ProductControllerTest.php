<?php

namespace Tests;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductFamily;

/**
 * @group controllers
 * @group api
 */
class ProductControllerTest extends TestCase
{
    protected $baseStructure = [
        'id',
        'name',
        'price'
    ];

    public function testIndex()
    {
        $response = $this->get($this->url('products'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [$this->baseStructure],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testIndexWithAttributes()
    {
        $url = $this->url('products', [
            'includes' => 'attribute_groups,attribute_groups.attributes'
        ]);

        $response = $this->get($url, [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [[
                'id',
                'name',
                'price',
                'attribute_groups' => [
                    'data' => [
                        [
                            'id', 'attributes' => [
                                'data' => [
                                    ['id']
                                ]
                            ]
                        ]
                    ]
                ],
            ]],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testAttributesDontShowByDefault()
    {
        $url = $this->url('products');

        $response = $this->get($url, [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);
        $data = json_decode($response->getContent(), true);

        $this->assertTrue(empty($data['data'][0]['attribute_groups']));

        $this->assertEquals(200, $response->status());
    }

    public function testIndexWithFamily()
    {
        $url = $this->url('products', [
            'includes' => 'family'
        ]);

        $response = $this->get($url, [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [[
                'id',
                'name',
                'price',
                'family' => ['data' => ['id']]
            ]],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testIndexWithFamilyAndAttributes()
    {
        $url = $this->url('products', [
            'includes' => 'family,attribute_groups,attribute_groups.attributes'
        ]);

        $response = $this->get($url, [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [[
                'id',
                'name',
                'price',
                'attribute_groups' => [
                    'data' => [
                        [
                            'id', 'attributes' => [
                                'data' => [
                                    ['id']
                                ]
                            ]
                        ]
                    ]
                ],
                'family' => ['data' => ['id']]
            ]],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testUnauthorisedIndex()
    {
        $response = $this->get($this->url('products'), [
            'Authorization' => 'Bearer foo.bar.bing',
            'Accept' => 'application/json'
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testShow()
    {
        // Get a channel
        $id = Product::first()->encodedId();

        $response = $this->get($this->url('products/' . $id), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => $this->baseStructure
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testMissingShow()
    {
        $response = $this->get($this->url('products/123456'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $this->assertHasErrorFormat($response);

        $this->assertEquals(404, $response->status());
    }

    public function testStore()
    {
        $family = ProductFamily::create([
            'name' => 'Foo bar'
        ]);

        $response = $this->post(
            $this->url('products'),
            [
                'name' =>  [
                    "en" => "Spring water"
                ],
                'price' => 10,
                'family_id' => $family->encodedId()
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
            $this->url('products'),
            [],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'name', 'price', 'family_id'
        ]);

        $this->assertEquals(422, $response->status());
    }

    public function testInvalidLanguageStore()
    {
        $family = ProductFamily::create([
            'name' => 'Foo bar'
        ]);

        $response = $this->post(
            $this->url('products'),
            [
                'name' =>  [
                    "es" => "Spring water"
                ],
                'price' => 10,
                'family_id' => $family->encodedId()
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $this->assertHasErrorFormat($response);

        $this->assertEquals(422, $response->status());
    }

    public function testUpdate()
    {
        $id = Product::first()->encodedId();
        $response = $this->put(
            $this->url('products/' . $id),
            [
                'name' => [
                    'en' => 'Foo bar'
                ],
                'default' => true
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );
        $this->assertEquals(200, $response->status());
    }

    public function testMissingUpdate()
    {
        $response = $this->put(
            $this->url('products/123123'),
            [
                'name' => [
                    'en' => 'Foo bar'
                ]
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $this->assertEquals(404, $response->status());
    }

    public function testDestroy()
    {
        $product = Product::create([
            'name' =>  json_encode(['en' => "Spanish"]),
            'price' => 50
        ]);

        $response = $this->delete(
            $this->url('products/' . $product->encodedId()),
            [],
            ['Authorization' => 'Bearer ' . $this->accessToken()]
        );

        $this->assertEquals(204, $response->status());
    }
}
