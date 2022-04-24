<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // const CREATED_AT = 'user_creation_date';
    // const UPDATED_AT = 'user_update_date';
    protected $primaryKey = 'id';


    /**
     * Get the user listenings.
     */
    public function user_listenings()
    {
        return $this->hasMany(User_listening::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'spotify_id',
        'token'
    ];

}
