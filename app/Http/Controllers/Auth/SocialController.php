<?php

namespace App\Http\Controllers\Auth;

use App;
use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')
            ->scopes(['user_actions.fitness', 'publish_actions'])
            ->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }

        $this->findUser($user);

        App::call('App\Http\Controllers\FitnessController@getAll');

        return redirect()->action('HomeController@index');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findUser($facebookUser)
    {
        $authUser = User::where('facebook_id', $facebookUser->id)->first();

        if ($authUser){
            Auth::login($authUser, true);

            return $authUser;
        } elseif (Auth::check()) {
            $authUser = Auth::user()->update(
                [
                    'facebook_id' => $facebookUser->id,
                    'facebook_token' => $facebookUser->token
                ]
            );

            return $authUser;
        } else {
            $pass = str_random(12);
            $authUser = User::create(
                [
                    'name' => $facebookUser->name,
                    'email' => $facebookUser->email,
                    'facebook_id' => $facebookUser->id,
                    'facebook_token' => $facebookUser->token,
                    'password' => bcrypt($pass),
                ]
            );

            Auth::login($authUser, true);

            return $authUser;
        }
    }
}
