<?php

namespace App\Http\Controllers;

use App\Models\OrderType;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function default_required_data($isFrom = "index", $id = false){
        $data = [
                    "order_types" => OrderType::all(),
                    // "users"       => User::all(),
                    "months"      => ["January","February","March","April","May","June","July","August","September","October","November","December"],
                    "years"       => ["2024","2025","2026"],
                    "payment_statuses" => [["id" => 0, "name" => "Unpaid"], ["id" => 1, "name" => "Paid"]],
                    // "payment_methods" => [["id" => 1, "name" => "Cash"], ["id" => 2, "name" => "Cheque"], ["id" => 3, "name" => "Credit Card"], ["id" => 4, "name" => "Bank Transfer"], ["id" => 5, "name" => "Debit Card"]],
                    // "orders" => Order::whereRaw("MONTH(created_at) = MONTH(CURRENT_DATE())")->get()
                ];
        
        return $data;
    }

    public function index()
    {
        $data = self::default_required_data();
        return view('pages.schedule.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = self::default_required_data();
        return view("pages.schedule.form", $data);
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
