<?php

namespace App\Http\Controllers;

use App\PlayerVersion;
use Illuminate\Http\Request;

class PlayerVersionController extends Controller
{
    //
    public function index($version) {
        $pv = new PlayerVersion();
        $pv->version_id = $version;
        $pv->save();

        return 'success';
    }
}
