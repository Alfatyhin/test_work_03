<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zipcode extends Model
{
    protected $primaryKey = 'zip';
    protected $keyType = 'string';
    protected $fillable = ['zip', 'city', 'data'];
    protected $hidden = ['created_at', 'updated_at'];
}
