<?php

namespace Tests;

use Event;
use GetCandy\Api\Layouts\Models\Layout;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Api\Services\Hashids\BaseService as HashidService;

/**
 * @group middleware
 * @group api
 */
class AttributeDataValidationTest extends TestCase
{

    /**
     * @group new
     */
    public function testValidationFailsOnString()
    {
        Event::fake();
        $family = ProductFamily::first();
        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attributes' => 'Foo',
                'sku' => 'Foo',
                'family_id' => $family->encodedId(),
                'layout_id' => $layout,
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'attributes'
        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @group new
     */
    public function testValidationFailsOnFirstMalformed()
    {
        Event::fake();
        $family = ProductFamily::first();
        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attributes' => [
                    'name' => 'foo'
                ],
                'sku' => 'Foo',
                'family_id' => $family->encodedId(),
                'layout_id' => $layout,
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'attributes'
        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @group new
     */
    public function testValidationFailsOnSecondMalformed()
    {
        Event::fake();
        $family = ProductFamily::first();
        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attributes' => [
                    'name' => ['foo']
                ],
                'sku' => 'Foo',
                'family_id' => $family->encodedId(),
                'layout_id' => $layout,
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'attributes'
        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @group new
     */
    public function testValidationFailsOnThirdMalformed()
    {
        Event::fake();
        $family = ProductFamily::first();
        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attributes' => [
                    'name' => ['foo' => 'bar']
                ],
                'sku' => 'Foo',
                'family_id' => $family->encodedId(),
                'layout_id' => $layout,
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'attributes'
        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @group new
     */
    public function testValidationFailsOnFourthMalformed()
    {
        Event::fake();
        $family = ProductFamily::first();
        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attributes' => [
                    'name' => ['foo' => ['en' => 'Baz']]
                ],
                'sku' => 'Foo',
                'family_id' => $family->encodedId(),
                'layout_id' => $layout,
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );

        $response->assertJsonStructure([
            'attributes'
        ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @group new
     */
    public function testValidationPassed()
    {
        Event::fake();

        $family = ProductFamily::first();

        $layout = Layout::first()->encodedId();

        $response = $this->post(
            $this->url('products'),
            [
                'attributes' => [
                    'name' => [
                        'ecommerce' => [
                            'en' => 'Foo'
                        ]
                    ]
                ],
                'sku' => 'Foo',
                'family_id' => $family->encodedId(),
                'layout_id' => $layout,
            ],
            [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]
        );
        $this->assertEquals(200, $response->status());
    }
}
