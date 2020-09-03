<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class ApiController extends Controller
{
    // Definir funcionalidades relacionadas a la API
    use ApiResponser;

    public function __construct(){

    }
}
