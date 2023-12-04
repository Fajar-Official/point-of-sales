<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    /**
     * API
     */
    public function api()
    {
        $result = Expense::all();
        return datatables()->of($result)->addIndexColumn()->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.expense.index');
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
            // Create a new Expense instance and save it to the database
            Expense::create([
                'description' => $request->input('description'),
                'amount' => $request->input('amount'),
            ]);

            // Return a JSON response indicating success
            return response()->json('Expense created successfully', 201);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating expense: ' . $e->getMessage());

            // Return a JSON response indicating an internal server error
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return response()->json($expense);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        try {
            // Update the expense with the new data
            $expense->update([
                'description' => $request->input('description'),
                'amount' => $request->input('amount'),
            ]);

            // Return a JSON response indicating success
            return response()->json('Expense updated successfully', 200);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating expense: ' . $e->getMessage());

            // Return a JSON response indicating an internal server error
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
            return response()->json('Category deleted successfully', 204);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }
}
