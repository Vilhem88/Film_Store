<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function count(){
        return response()->json(['count' => Movie::count()]);
    }
}
