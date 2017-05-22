<?php

namespace Tests;

use Laravel\Passport\Client;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing']);
        $this->artisan('db:seed', ['--class' => 'TestingSeeder']);
        $this->artisan('passport:install');
    }
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:oDHSn2Yha/oLyIcGZJH72bEY6VPMkuX+D2RGJGOlBC4=');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('app.debug', true);
        $app['config']->set('auth.guards.api.driver', 'passport');
        $app->registerConfiguredProviders();

        /**
         * Use Our model for authentication
         */
        $app['config']->set('auth.providers.users.model', \GetCandy\Api\Models\User::class);

        /**
         * Make sure Hashids has at least one default value
         */
        $connections = [
            'attribute',
            'attribute_group',
            'channel',
            'currency',
            'language',
            'product',
            'product_attribute',
            'product_family',
            'tax',
            'user'
        ];

        foreach ($connections as $connection) {
            $app['config']->set('hashids.connections.' . $connection, [
                'alphabet' => 'abcdefghijklmnopqrstuvwxyz1234567890',
                'salt' => 'test_salt',
                'length' => 8
            ]);
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            'Vinkla\Hashids\HashidsServiceProvider'
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
        ];
    }

    protected function url($path, $query = null)
    {
        $url = '/api/' . config('getcandy_api.version', 'v1') . '/' . $path . ($query ? '?' . http_build_query($query) : null);
        return $url;
    }

    protected function getContent($response)
    {
        return json_decode($response->getContent(), true);
    }

    protected function accessToken()
    {
        $client = Client::first();

        $response = $this->post('/oauth/token', [
            'username' => 'alec@neondigital.co.uk',
            'password' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'grant_type' => 'password'
        ], ['Accept' => 'application/json']);

        $content = $this->getContent($response);

        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token'
        ]);

        $this->assertEquals(200, $response->getStatusCode());


        return $content['access_token'];
    }

    protected function assertHasErrorFormat($response)
    {
        $response->assertJsonStructure([
            'error' => ['http_code', 'message']
        ]);
    }
}
