<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class usersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return $users;
    }
}
