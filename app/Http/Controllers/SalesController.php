<?php

namespace App\Http\Controllers;

use App\Models\sales;
use App\Http\Requests\StoresalesRequest;
use App\Http\Requests\UpdatesalesRequest;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    
    public function showSalesTransactions()
    {
        return view('Kasir.SalesTransactions');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoresalesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesalesRequest $request, sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sales $sales)
    {
        //
    }

    public function salesReport()
    {
        
    }
}
