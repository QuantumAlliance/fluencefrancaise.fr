<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'smtp_host', 
        'smtp_port', 
        'smtp_username', 
        'smtp_password', 
        'from_email', 
        'from_name',
        'google_client_id',
        'google_client_secret',
        'google_redirect_uri',
        'google_access_token',
        'google_refresh_token',
        'google_from_email',
        'google_from_name'
    ];
}
