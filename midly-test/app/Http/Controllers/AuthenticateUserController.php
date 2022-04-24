<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class AuthenticateUserController extends Controller
{
  public function __construct()
  {
    $this->userRepository = new UserRepository();
  }

  /**
     * implements spotify user auth.
     *
     * @return null
     */
  public function fetchUserSpotifyData()
  {
    $client_id = env('SPOTIFY_CLIENT_ID'); 
    $redirect = env('SPOTIFY_REDIRECT_URI'); 
    $state = Str::random(40);
    $scope = 'user-read-private user-read-email user-read-recently-played';

    $url = 'https://accounts.spotify.com/authorize?response_type=code&client_id='.$client_id.'&scope='.$scope.'&redirect_uri='.$redirect.'&state='.$state.'&show_dialog=true';
    return redirect()->away($url);

  }

   /**
     * receives user data passed to the callback url on spofify.
     *  and updates exisiting user record in the db or creates a new user
     * @return user 
     */
  public function authenticateUser(Request $request)
  {
    try {
      $client_secret = env('SPOTIFY_CLIENT_SECRET'); 
      $client_id = env('SPOTIFY_CLIENT_ID'); 
      $code = $request->query('code');
      $redirect = env('SPOTIFY_REDIRECT_URI'); 

      $token = base64_encode($client_id.':'.$client_secret);

      $response = HTTP::withHeaders([
        'Authorization' =>  'Basic '.$token,
        ])->asForm()->post('https://accounts.spotify.com/api/token',[
        'code' => $code,
        'redirect_uri' => $redirect,
        'grant_type' => 'authorization_code'
        ]);
        $resp = json_decode($response);

      if($response->clientError()){
        return $this->createErrorMessage($resp->error_description, 500, $resp->error);
      }

      $access_token = $resp->access_token;
      $refresh_token = $resp->refresh_token;

      $userDataResponse = HTTP::withHeaders([
        'Authorization' => 'Bearer '.$access_token
      ])->get('https://api.spotify.com/v1/me');

      $userData = json_decode($userDataResponse);

      if($userDataResponse->clientError()){
        return $this->createErrorMessage($userData->error_description, 500, $userData->error);
      }

      $userPayload = [
        'name' => $userData->display_name,
        'email' => $userData->email,
        'token' => $refresh_token,
        'spotify_id' => $userData->id,
        'avatar' => empty($userData->images) ? '' : $userData->images[0]->url
      ];

      $user = $this->userRepository->CreateOrUpdateUserBySpotifyId($userPayload);

      unset($user->created_at);
      unset($user->updated_at);

       return $this->createSuccessResponse(true, $user, 'Authentication successful', 200);

    } catch (Exception $e){
      return $this->createErrorMessage($e->getMessage(), 500, $e);
    }
  }

}