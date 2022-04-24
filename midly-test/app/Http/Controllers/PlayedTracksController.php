<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\PlayedTracksRepository;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class PlayedTracksController extends Controller
{
  public function __construct()
  {
   
  }

  public function fetchUsersRecentTracks()
  {
    $this->userRepository = new UserRepository();
    $this->tracksRepository = new PlayedTracksRepository();
    
    try {
      $client_secret = env('SPOTIFY_CLIENT_SECRET'); 
      $client_id = env('SPOTIFY_CLIENT_ID'); 
      $token = base64_encode($client_id.':'.$client_secret);

      // add pagination here
      $users = $this->userRepository->getAllUsers();

      if($users){
      foreach($users as $user){

       // using the refresh_token, fetch a new access_token
       $refresh_token = $user->token;
       $response = HTTP::withHeaders([
       'Authorization' =>  'Basic '.$token,
      ])->asForm()->post('https://accounts.spotify.com/api/token', [
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token
      ]);
      $resp = json_decode($response);

      if($response->clientError()){
      
        return $this->createErrorMessage($resp->error_description, 500, $resp->error);
      }

      $yesterday = Carbon::yesterday();
      $after = strtotime($yesterday);

       // use the new access_token, fetch the users recently played
       $userRecentlyPlayedTracks =  HTTP::withHeaders([
        'Authorization' =>  'Bearer '.$resp->access_token,
       ])->get('https://api.spotify.com/v1/me/player/recently-played?after='.$after);
       $userPlayedData = json_decode($userRecentlyPlayedTracks);

       if($userRecentlyPlayedTracks->clientError()){
        return $this->createErrorMessage($userPlayedData->error->message, $userPlayedData->error->status);
      }
       // save the fetched data in the db
       $userPlayedList = $userPlayedData->items;
       if(!empty($userPlayedList)){
         $tracklist = [];
        foreach($userPlayedList as $recentTracks){
          $track = [
            'user_id' => $user->id,
            'artist_id' => $recentTracks->track->artists[0]->id,
            'spotify_track_id' => $recentTracks->track->id,
            'track_name' => $recentTracks->track->name,
            'played_at' => $recentTracks->played_at
          ];
          array_push($tracklist, $track);
        }
        $tracks = $this->tracksRepository->AddTracks($tracklist);
        return $this->createSuccessResponse(true, $tracks, 'Successful', 200);
       }
      }
     
    }
    } catch (Exception $e){
      return $this->createErrorMessage($e->getMessage(), 500, $e);
    }
  }
}