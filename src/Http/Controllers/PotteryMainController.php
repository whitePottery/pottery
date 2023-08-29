<?php

namespace Pottery\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagePotteryController extends Controller
{


    public function index()
    {
        return view('pottery::main');
    }
}
