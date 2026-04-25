<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;

class TruckController extends Controller
{
    public function index()
    {
        return view('livreur.truck.index');
    }
}
