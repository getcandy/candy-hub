<?php

namespace Tests;

use GetCandy\Api\Layouts\Models\Layout;
use GetCandy\Api\Pages\Models\Page;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Events\ProductCreatedEvent;
use GetCandy\Events\ProductUpdatedEvent;
use Event;

/**
 * @group controllers
 * @group api
 * @group products
 */
class ProductControllerTest extends TestCase
{
    protected $baseStructure = [
        'id',
        'attribute_data' => ['name']
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
                'attribute_data' => ['name'],
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
                'attribute_data' => ['name'],
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
                'attribute_data' => ['name'],
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

    /**
     * @group fail
     * @return [type] [description]
     */
    public function testStore()
    {
        Event::fake();

        $family = ProductFamily::create([
            'attribute_data' => ['name' => ['en' => 'Foo bar']]
        ]);

        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attributes' =>  [
                    'name' => [
                        "ecommerce" => [
                            "en" => "Spring water"
                        ]
                    ]
                ],
                'family_id' => $family->encodedId(),
                'layout_id' => $layout,
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
            'attributes', 'family_id'
        ]);

        $this->assertEquals(422, $response->status());
    }

    public function testInvalidLanguageStore()
    {
        $family = ProductFamily::create([
            'name' => ['en' => 'Foo bar']
        ]);

        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attribute_data' => [
                    'name' =>  [
                        'ecommerce' => [
                            'en' => 'Foo'
                        ]
                    ]
                ],
                'family_id' => $family->encodedId(),
                'slug' => 'spring-water',
                'layout_id' => $layout,
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $this->assertHasErrorFormat($response);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @group failing
     */
    public function testUpdate()
    {
        Event::fake();

        $id = Product::first()->encodedId();
        $response = $this->put(
            $this->url('products/' . $id),
            [
                'attribute_data' => [
                    'name' =>  [
                        'ecommerce' => [
                            'en' => 'Foo'
                        ]
                    ]
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
                'attribute_data' => [
                    'name' =>  [
                        'ecommerce' => [
                            'en' => 'Foo'
                        ]
                    ]
                ],
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
            'attribute_data' => [
                'name' =>  [
                    'ecommerce' => [
                        'en' => 'Foo'
                    ]
                ]
            ]
        ]);

        $response = $this->delete(
            $this->url('products/' . $product->encodedId()),
            [],
            ['Authorization' => 'Bearer ' . $this->accessToken()]
        );

        $this->assertEquals(204, $response->status());
    }
}
