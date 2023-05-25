<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\Controller;

class UserController  extends Controller
{
    public function show(int $id)
    {
        return view("users/show", ["id" => $id]);
    }
}