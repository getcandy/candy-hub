<?php

namespace GetCandy\Api\Tests;

use GetCandy\Api\Models\Product;
use GetCandy\Api\Services\Hashids\BaseService as HashidService;

/**
 * @group middleware
 * @group api
 */
class SetLocaleMiddlewareTest extends TestCase
{
    public function testDefaultLanguage()
    {
        $response = $this->get($this->url('users'), [
            'Authorization' => 'Bearer ' . $this->accessToken(),
            'Accept' => 'application/json'
        ]);
        $response->assertJsonFragment(['lang' => 'en']);
    }

    public function testSetFrenchLanguage()
    {
        \GetCandy\Api\Models\Language::create([
            'code' => 'fr',
            'name' => 'French',
            'enabled' => 1,
            'default' => 0
        ]);
        $response = $this->get($this->url('users'), [
            'Authorization' => 'Bearer ' . $this->accessToken(),
            'Accept-Language' => 'fr'
        ]);
        $response->assertJsonFragment(['lang' => 'fr']);
    }

    public function testSetInvalidLanguage()
    {
        $response = $this->get($this->url('users'), [
            'Authorization' => 'Bearer ' . $this->accessToken(),
            'Accept-Language' => 'dk'
        ]);
        $this->assertEquals(400, $response->status());
    }
}
