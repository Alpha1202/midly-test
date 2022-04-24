<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\User_listening;
use Illuminate\Support\Facades\DB;

class PlayedTracksRepository {

  public function __construct() 
  {
    $this->user = new User();
    $this->userTracks = new User_listening();
  }


  /**
     * adds a new track
     *
     * @return user_Track
     */
  public function AddTracks($payload)
  {
      $userTracks = $this->userTracks->insert($payload);
      return $userTracks;
  }
}