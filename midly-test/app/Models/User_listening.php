<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_listening extends Model
{
    use HasFactory;

    const CREATED_AT = 'played_at';
    const UPDATED_AT = 'play_update_date';
}

