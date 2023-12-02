<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * API
     */
    public function api()
    {
        $result = Product::all();
        return datatables()->of($result)->addIndexColumn()->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.product.index');
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
            Product::create([
                'category_id' => $request->input('category_id'),
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'brand' => $request->input('brand'),
                'purchase_price' => $request->input('purchase_price'),
                'selling_price' => $request->input('selling_price'),
                'discount' => $request->input('discount'),
                'stock' => $request->input('stock'),
            ]);

            return response()->json('Product created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());

            return response()->json('Error creating product: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $product->update([
                'category_id' => $request->input('category_id'),
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'brand' => $request->input('brand'),
                'purchase_price' => $request->input('purchase_price'),
                'selling_price' => $request->input('selling_price'),
                'discount' => $request->input('discount'),
                'stock' => $request->input('stock'),
            ]);

            return response()->json('Product updated successfully', 200);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->json('Product deleted successfully', 204);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }
}
