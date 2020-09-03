<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directorio extends Model
{
    protected $table="directorios";
    protected $fillable = ['name','email','direction','phone','avatar'];
    protected $hidden = ['created_at','updated_at'];
}
