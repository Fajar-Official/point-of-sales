<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function api()
    {
        $purchase = Purchase::all();
        return datatables()->of($purchase)->addIndexColumn()->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::orderBy('name')->get();

        return view('pages.purchase.index', compact('supplier'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $purchases = new Purchase();
        $purchases->supplier_id = $id;
        $purchases->total_item = 0;
        $purchases->total_price = 0;
        $purchases->discount = 0;
        $purchases->payment = 0;
        $purchases->save();

        session(['id' => $purchases->id]);
        session(['supplier_id' => $purchases->supplier_id]);

        return redirect()->route('purchase_details.index');
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
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
