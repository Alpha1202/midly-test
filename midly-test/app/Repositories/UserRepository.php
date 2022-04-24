<?php

namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository {

  public function __construct() 
  {
    $this->user = new User();
  }

   /**
     * check if a user email exists in the db and returns it.
     *
     * @return user || []
     */
  public function getAllUsers()
  {
    $users = $this->user->select('*')->get();

    return $users;
  }

  /**
     * Create or update user
     *
     * @return user
     */
  public function CreateOrUpdateUserBySpotifyId($payload)
  {
      $user = $this->user->updateOrCreate(['spotify_id' => $payload['spotify_id']],
      [
        'name' => $payload['name'],
        'email' => $payload['email'],
        'avatar' => $payload['avatar'],
        'token' => $payload['token']
      ]);
      return $user;
  }
}