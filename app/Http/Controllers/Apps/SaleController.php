<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\SalesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    /**
     * index
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Apps/Sales/Index');
    }

    /**
     * filter
     *
     * @param  mixed $request
     * @return \Inertia\Response
     */
    public function filter(Request $request)
    {
        $request->validate([
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data sales by range date
        $sales = Transaction::with('cashier', 'customer')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->get();

        //get total sales by range date
        $total = Transaction::whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->sum('grand_total');

        return Inertia::render('Apps/Sales/Index', [
            'sales'    => $sales,
            'total'    => (int) $total
        ]);
    }
    /**
     * export
     *
     * @param  mixed $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        return Excel::download(new SalesExport($request->start_date, $request->end_date), 'sales : ' . $request->start_date . ' — ' . $request->end_date . '.xlsx');
    }

    /**
     * pdf
     *
     * @param  mixed $request
     * @return void
     */
    public function pdf(Request $request)
    {
        //get sales by range date
        $sales = Transaction::with('cashier', 'customer')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total sales by range daate
        $total = Transaction::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('grand_total');

        //load view PDF with data
        $pdf = PDF::loadView('exports.sales', compact('sales', 'total'));

        //return PDF for preview / download
        return $pdf->download('sales : ' . $request->start_date . ' — ' . $request->end_date . '.pdf');
    }
}
