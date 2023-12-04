<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    /**
     * API
     */
    public function api()
    {
        $suppliers = Supplier::all();
        return datatables()->of($suppliers)->addIndexColumn()->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.supplier.index');
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

    public function store(Request $request)
    {
        try {
            // Create a new Supplier instance and save it to the database
            Supplier::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);

            // Return a JSON response indicating success
            return response()->json('Supplier created successfully', 201);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating supplier: ' . $e->getMessage());

            // Return a JSON response indicating an internal server error
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        try {
            // Update the supplier with the new data
            $supplier->update([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);

            // Return a JSON response indicating success
            return response()->json('Supplier updated successfully', 200);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating supplier: ' . $e->getMessage());

            // Return a JSON response indicating an internal server error
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return response()->json('Category deleted successfully', 204);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }
}
