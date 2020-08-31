<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directorio extends Model
{
    protected $table="directorios";
    protected $fillable = ['name','email','direction','phone','urlavatar'];
    protected $hidden = ['created_at','updated_at'];
}
