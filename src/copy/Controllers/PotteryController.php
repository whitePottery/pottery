<?php

    namespace App\Http\Controllers\Pottery;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class PotteryController extends Controller
    {


        public function index() {


            return view('pottery.example');

        }
    }
