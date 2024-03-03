<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseDetailController extends Controller
{
    public function api($id)
    {
        $purchaseDetail = PurchaseDetail::with('product')
            ->where('purchase_id', $id)
            ->get();

        // $data = array();
        // $total = 0;
        // $total_item = 0;
        // foreach ($purchaseDetail as $key => $item) {
        //     $row = array();

        //     $row['code'] = $item->product['code'];
        //     $row['product.name'] = $item->product['name'];
        //     $row['purchase_price'] = $item->purchase_price;
        //     $row['quantity'] = $item->quantity;
        //     $row['subtotal'] = $item->subtotal;
        //     $data[] = $row;

        //     $total += $item->purchase_price * $item->quantity;
        //     $total_item += $item->quantity;
        // }
        // $data[] = [
        //     '<div class="total hide">' . $total . '</div> <div class="total_item hide">' . $total_item . '</div>',
        //     '',
        //     '',
        //     '',
        //     '',
        //     '',
        // ];


        return datatables()->of($purchaseDetail)->addIndexColumn()->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase_id = session('id');
        $product = Product::orderBy('name')->get();
        $supplier = Supplier::find(session('supplier_id'));

        if (!$supplier) {
            abort(404);
        }
        return view('pages.purchase_detail.index', compact('purchase_id', 'product', 'supplier'));
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
        $product = Product::where('id', $request->product_id)->first();

        if (!$product) {
            return response()->json('Data Gagal Disimpan', 404);
        }

        $detail = new PurchaseDetail();
        $detail->purchase_id = $request->purchase_id;
        $detail->product_id = $product->id;
        $detail->purchase_price = $product->purchase_price;
        $detail->quantity = 1;
        $detail->subtotal = $product->purchase_price;
        $detail->save();

        return response()->json('Data Berhasil Disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseDetail $purchaseDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseDetail $purchaseDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseDetail $purchaseDetail)
    {
        $purchaseDetail->quantity = $request->quantity;
        $purchaseDetail->subtotal = $purchaseDetail->product->purchase_price * $request->quantity;
        $purchaseDetail->save();

        // Kirim kembali respons berupa objek JSON yang berisi informasi detail pembelian yang diperbarui
        return response()->json([
            'message' => 'Purchase detail updated successfully',
            'subtotal' => $purchaseDetail->subtotal
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseDetail $purchaseDetail)
    {
        try {
            $purchaseDetail->delete();

            return response()->json('Item deleted successfully', 204);
        } catch (\Exception $e) {
            Log::error('Error deleting item: ' . $e->getMessage());
            return response()->json('Internal Server Error = ' . $e->getMessage(), 500);
        }
    }
}
