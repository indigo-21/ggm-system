<?php

namespace App\Http\Controllers;

use App\Models\OrderInscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderInscriptionController extends Controller
{
    public function upsert(Request $request){
        dd($request);
    }
}
