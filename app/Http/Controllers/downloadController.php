<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class downloadController extends Controller
{
    public function download_media($id){

        return media_url($id);

    }
}
