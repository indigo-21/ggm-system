<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OrderType;
use App\Models\Location;
use App\Models\Cemetery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Response;
use Auth;
class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $data = [
            "order_types" => OrderType::all(),
            "users"       => User::all(),
            "months"      => ["January","February","March","April","May","June","July","August","September","October","November","December"],
            "years"       => ["2024","2025","2026"],
        ];
        return view("pages.quote.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $data = [
            "auth_session"  => User::find(Auth::id()),
            "order_types"   => OrderType::all(),
            "locations"     => Location::all(),
            "users"         => User::all(),
            "cemeteries"    => Cemetery::all(),
            "titles"        => ["None", "Mr","Mrs","Miss","Ms","Dr"],
            "months"        => ["January","February","March","April","May","June","July","August","September","October","November","December"],
            "years"         => ["2024","2025","2026"],
        ];
        return view("pages.quote.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
