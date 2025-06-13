<?php

namespace App\Http\Controllers;

use App\Models\purchases;
use App\Http\Requests\StorepurchasesRequest;
use App\Http\Requests\UpdatepurchasesRequest;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorepurchasesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(purchases $purchases)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchases $purchases)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepurchasesRequest $request, purchases $purchases)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(purchases $purchases)
    {
        //
    }
}
