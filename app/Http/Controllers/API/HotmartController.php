<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HotmartController extends Controller
{
    public function access(Request $request)
    {
    	return $request->all();
    }
}
