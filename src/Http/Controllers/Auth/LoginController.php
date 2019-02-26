<?php

namespace GetCandy\Hub\Http\Controllers\Auth;

use CandyClient;
use Illuminate\Http\Request;
use GetCandy\Hub\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectPath = '/hub ';

    protected function redirectTo()
    {
        return route('hub.index');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('hub::auth.login');
    }

     /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (class_exists(CandyClient::class)) {
            // They are authenticated, so lets just get them a token
            // That they can use for requests.
            $token = CandyClient::getUserToken($request->email, $request->password);

            $this->setSessionTokens(
                $request,
                $token->getBody()->access_token,
                $token->getBody()->refresh_token,
                $token->getBody()->expires_in
            );
        }
    }

    /**
     * Sets the session tokens
     *
     * @param string $token
     * @param string $refresh
     * @param int $expires
     * @return void
     */
    protected function setSessionTokens($request, $token, $refresh, $expires)
    {
        $tokenExpiry = \Carbon\Carbon::now()->addMinutes($expires / 60);
        $request->session()->put('access_token', $token);
        $request->session()->put('refresh_token', $refresh);
        $request->session()->put('token_expires_at', $tokenExpiry);
        CandyClient::setToken($token);
    }
}
