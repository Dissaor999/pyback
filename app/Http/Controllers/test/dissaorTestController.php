<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Models\User;


class dissaorTestController extends Controller
{
  public function getusers()
  {
    return response(User::all());
  }
}
