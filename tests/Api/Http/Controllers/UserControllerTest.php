<?php

namespace GetCandy\Api\Tests;

use GetCandy\Api\Models\Product;

/**
 * @group controllers
 * @group api
 */
class UserControllerTest extends TestCase
{
    protected $baseStructure = [
        'id',
        'name',
        'email',
        'role',
        'permissions'
    ];

    public function testIndex()
    {
        $response = $this->get($this->url('users'), [
            'Authorization' => 'Bearer ' . $this->accessToken()
        ]);

        $response->assertJsonStructure([
            'data' => [$this->baseStructure],
            'meta' => ['pagination']
        ]);

        $this->assertEquals(200, $response->status());
    }

    public function testStore()
    {
        $response = $this->post($this->url('users'), [
                'email' => 'dom@neondigital.co.uk',
                'password' => 'password',
                'role' => \GetCandy\Api\Roles\SuperUserRole::class,
                'name' => 'Dom',
                'password_confirmation' => 'password'
            ], [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]);

        $this->assertEquals(200, $response->status());

        $response->assertJsonStructure([
            'data' => $this->baseStructure
        ]);
    }

    public function testPasswordConfirmationFails()
    {
        $response = $this->post($this->url('users'), [
                'email' => 'dom@neondigital.co.uk',
                'password' => 'passwosrd',
                'role' => \GetCandy\Api\Roles\SuperUserRole::class,
                'name' => 'Dom',
                'password_confirmation' => 'password'
            ], [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]);

        $this->assertEquals(422, $response->status());

        $response->assertJsonStructure([
            'password'
        ]);
    }

    public function testValidationFailedStore()
    {
        $response = $this->post($this->url('users'), [], [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]);

        $this->assertEquals(422, $response->status());

        $response->assertJsonStructure([
            'password', 'email', 'name', 'role'
        ]);
    }

    public function testDuplicateEmailValidationErrorStore()
    {
        \GetCandy\Api\Models\User::create([
                'email' => 'person@neondigital.co.uk',
                'password' => 'password',
                'role' => \GetCandy\Api\Roles\SuperUserRole::class,
                'name' => 'Person',
        ]);

        $response = $this->post($this->url('users'), [
                'email' => 'person@neondigital.co.uk',
                'password' => 'password',
                'role' => \GetCandy\Api\Roles\SuperUserRole::class,
                'name' => 'Person',
                'password_confirmation' => 'password'
            ], [
                'Authorization' => 'Bearer ' . $this->accessToken()
            ]);

        $this->assertEquals(422, $response->status());

        $response->assertJsonStructure([
            'email'
        ]);
    }

    public function testCurrentUser()
    {
        // $response = $this->get($this->url('users/current'), [
        //     'Authorization' => 'Bearer ' . $this->accessToken()
        // ]);

        // $response->assertJsonStructure([
        //     'data' => $this->baseStructure
        // ]);

        // $this->assertEquals(200, $response->status());
    }
}
