<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Requesting\Request;

class HomeController extends Controller
{    
    public function index()
    {
        return view("home");
    }
}