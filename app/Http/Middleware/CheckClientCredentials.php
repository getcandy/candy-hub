<?php

namespace GetCandy\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckClientCredentials as BaseMiddleware;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Illuminate\Contracts\Encryption\Encrypter;
use Laravel\Passport\Passport;
use Firebase\JWT\JWT;
use Laravel\Passport\TransientToken;

class CheckClientCredentials extends BaseMiddleware
{

    /**
     * The Resource Server instance.
     *
     * @var \League\OAuth2\Server\ResourceServer
     */
    private $server;

    private $encrypter;

    /**
     * Create a new middleware instance.
     *
     * @param  \League\OAuth2\Server\ResourceServer  $server
     * @return void
     */
    public function __construct(ResourceServer $server, Encrypter $encrypter)
    {
        $this->server = $server;
        $this->encrypter = $encrypter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$scopes
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        $psr = (new DiactorosFactory)->createRequest($request);

        $cookies = $psr->getCookieParams();

        if (!empty($cookies[Passport::cookie()])) {
            $token = $this->decodeJwtTokenCookie($cookies[Passport::cookie()]);

            // We will compare the CSRF token in the decoded API token against the CSRF header
            // sent with the request. If the two don't match then this request is sent from
            // a valid source and we won't authenticate the request for further handling.
            // if (! $this->validCsrf($token, $request) ||
            //     time() >= $token['expiry']) {
            //     throw new AuthenticationException;;
            // }
            dd(new TransientToken);
            $psr = $psr->withAccessToken(new TransientToken);

            dd($psr);
        }

        try {

            dd($psr);
            $psr = $this->server->validateAuthenticatedRequest($psr);


        } catch (OAuthServerException $e) {
            throw new AuthenticationException;
        }

        if ($userId = $psr->getAttribute('oauth_user_id')) {
            \Auth::loginUsingId($userId);
        }

        $this->validateScopes($psr, $scopes);

        return $next($request);
    }

    /**
     * Decode and decrypt the JWT token cookie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function decodeJwtTokenCookie($cookie)
    {
        return (array) JWT::decode(
            $this->encrypter->decrypt($cookie),
            $this->encrypter->getKey(),
            ['HS256']
        );
    }

    /**
     * Determine if the CSRF / header are valid and match.
     *
     * @param  array  $token
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function validCsrf($token, $request)
    {
        return isset($token['csrf']) && hash_equals(
            $token['csrf'], (string) $request->header('X-CSRF-TOKEN')
        );
    }
}
