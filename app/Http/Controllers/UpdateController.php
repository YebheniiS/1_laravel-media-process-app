<?php

namespace App\Http\Controllers;

use App\Updates\update20;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    //
    public function applyUpdate20(update20 $update20)
    {
        return $update20->apply();
    }
}
