<?php

declare(strict_types=1);

namespace App\Controllers;

class UserController
{
    public function show(int $id)
    {
        return view("users/show", ["id" => $id]);
    }
}