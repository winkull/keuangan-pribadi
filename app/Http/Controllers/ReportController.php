<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $product_id = $request->product_id;
                $customer_id = $request->customer_id;
                $status = $request->status;
                $dates = $request->date;
                $date = explode(' - ', $dates);
                $fromDate = Carbon::parse($date[0])->format('Y-m-d');
                $toDate = Carbon::parse($date[1])->format('Y-m-d');

                $transactions = $this->query($product_id, $customer_id, $status, $fromDate, $toDate);

                if ($transactions->toArray() == []) {
                    return response()->json([
                        'success' => true,
                        'tbody' => '<tr>
                                        <td colspan="10">
                                            <Center>No data available in table</Center>
                                        </td>
                                    </tr>',
                        'tfoot' => null,
                        'data' => false
                    ]);
                }

                $tbody = "";
                $purchase_price = 0;
                $selling_price = 0;
                $profit = 0;
                foreach ($transactions as $key => $item) {
                    $purchase_price += $item->purchase_price;
                    $selling_price += $item->selling_price;
                    $profit += $item->profit;

                    $key = $key + 1;
                    $item->purchase_price = number_format($item->purchase_price);
                    $item->selling_price = number_format($item->selling_price);
                    $item->profit = number_format($item->profit);
                    $item->balance = number_format($item->balance);
                    $item->status = $item->status == 1 ? '<span class="badge badge-success">Lunas</span>' : ($item->status == 2 ? '<span class="badge badge-info">Top Up</span>' : ($item->status == 3 ? '<span class="badge badge-warning">Substract</span>' : '<span class="badge badge-danger">Hutang</span>'));
                    $item->transaction = $item->transaction ? $item->transaction : '-';
                    $item->customer = $item->customer ? $item->customer : '-';
                    $item->description = $item->description ? $item->description : '-';
                    $tbody .= "<tr>
                                    <td>$key</td>
                                    <td>$item->date</td>
                                    <td>$item->transaction</td>
                                    <td>$item->customer</td>
                                    <td class='text-end'>$item->purchase_price</td>
                                    <td class='text-end'>$item->selling_price</td>
                                    <td class='text-end'>$item->profit</td>
                                    <td class='text-end'>$item->balance</td>
                                    <td>$item->status</td>
                                    <td>$item->description</td>
                                </tr>";
                }
                $purchase_price = number_format($purchase_price);
                $selling_price = number_format($selling_price);
                $profit = number_format($profit);
                $tfoot = "<tr>
                            <td colspan='4'>Total</td>
                            <td class='text-end'>$purchase_price</td>
                            <td class='text-end'>$selling_price</td>
                            <td class='text-end'>$profit</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>";

                return response()->json([
                    'success' => true,
                    'tbody' => $tbody,
                    'tfoot' => $tfoot,
                    'data' => true
                ]);
            }
            $datas['products'] = Product::all();
            $datas['customers'] = Customer::all();
            return view('reports.list', $datas);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function print(Request $request)
    {
        try {
            $product_id = $request->product_id;
            $customer_id = $request->customer_id;
            $status = $request->status;
            $dates = $request->date;
            $date = explode(' - ', $dates);
            $fromDate = Carbon::parse($date[0])->format('Y-m-d');
            $toDate = Carbon::parse($date[1])->format('Y-m-d');

            $transactions = $this->query($product_id, $customer_id, $status, $fromDate, $toDate);

            if ($transactions->toArray() == []) {
                $datas['body'] = '<tr>
                                    <td colspan="10">
                                        <Center>No data available in table</Center>
                                    </td>
                                </tr>';
                $datas['footer'] = null;
                return view('reports.print', $datas);
            }

            $tbody = "";
            $purchase_price = 0;
            $selling_price = 0;
            $profit = 0;
            foreach ($transactions as $key => $item) {
                $purchase_price += $item->purchase_price;
                $selling_price += $item->selling_price;
                $profit += $item->profit;

                $item->key = $key + 1;
                $item->purchase_price = number_format($item->purchase_price);
                $item->selling_price = number_format($item->selling_price);
                $item->profit = number_format($item->profit);
                $item->balance = number_format($item->balance);
                $item->status = $item->status == 1 ? '<span class="badge badge-success">Lunas</span>' : ($item->status == 2 ? '<span class="badge badge-info">Top Up</span>' : ($item->status == 3 ? '<span class="badge badge-warning">Substract</span>' : '<span class="badge badge-danger">Hutang</span>'));
                $item->transaction = $item->transaction ? $item->transaction : '-';
                $item->customer = $item->customer ? $item->customer : '-';
                $item->description = $item->description ? $item->description : '-';
                $tbody .= "<tr>
                                    <td>$key</td>
                                    <td>$item->date</td>
                                    <td>$item->transaction</td>
                                    <td>$item->customer</td>
                                    <td class='text-end'>$item->purchase_price</td>
                                    <td class='text-end'>$item->selling_price</td>
                                    <td class='text-end'>$item->profit</td>
                                    <td class='text-end'>$item->balance</td>
                                    <td>$item->status</td>
                                    <td>$item->description</td>
                                </tr>";
            }
            $purchase_price = number_format($purchase_price);
            $selling_price = number_format($selling_price);
            $profit = number_format($profit);
            $tfoot = "<tr>
                            <td colspan='4'>Total</td>
                            <td class='text-end'>$purchase_price</td>
                            <td class='text-end'>$selling_price</td>
                            <td class='text-end'>$profit</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>";

            $datas['body'] = $tbody;
            $datas['footer'] = $tfoot;
            return view('reports.print', $datas);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $product_id = $request->product_id;
        $customer_id = $request->customer_id;
        $status = $request->status;
        $dates = $request->date;
        $date = explode(' - ', $dates);
        $fromDate = Carbon::parse($date[0])->format('Y-m-d');
        $toDate = Carbon::parse($date[1])->format('Y-m-d');

        $transactions = $this->query($product_id, $customer_id, $status, $fromDate, $toDate);

        $datas = [
            [
                'No', 'Date', 'Transaction', 'Customer', 'Price', 'Selling Price', 'Profit', 'Last Balance', 'Payment Status', 'Description'
            ]
        ];


        $purchase_price = 0;
        $selling_price = 0;
        $profit = 0;
        foreach ($transactions as $key => $item) {
            $purchase_price += $item->purchase_price;
            $selling_price += $item->selling_price;
            $profit += $item->profit;

            $item->status = $item->status == 1 ? 'Lunas' : ($item->status == 2 ? 'Top Up' : ($item->status == 3 ? 'Substract' : 'Hutang'));
            $item->transaction = $item->transaction ? $item->transaction : '-';
            $item->customer = $item->customer ? $item->customer : '-';
            $item->description = $item->description ? $item->description : '-';
            $item = $item->toArray();
            $datas[] = array_merge(['no' => $key + 1], $item);
        }
        $datas[] = ['', '', '', '', $purchase_price, $selling_price, $profit, '', '', ''];

        return Excel::download(new ReportExport($datas), 'Reports.xlsx');
    }

    public function query($product_id, $customer_id, $status, $fromDate, $toDate)
    {
        return Transaction::leftJoin('products', 'products.id', 'transactions.product_id')
            ->leftJoin('customers', 'customers.id', 'transactions.customer_id')
            ->selectRaw("
                transactions.created_at as date,
                products.name AS transaction,
                customers.name AS customer,
                transactions.purchase_price,
                transactions.selling_price,
                transactions.profit,
                transactions.balance,
                transactions.status,
                transactions.description
            ")
            ->when($product_id, function ($q) use ($product_id) {
                $q->where('products.id', $product_id);
            })
            ->when($customer_id, function ($q) use ($customer_id) {
                $q->where('customers.id', $customer_id);
            })
            ->when($status != null, function ($q) use ($status) {
                $q->where('transactions.status', $status);
            })
            ->whereDate('transactions.created_at', '>=', $fromDate)
            ->whereDate('transactions.created_at', '<=', $toDate)
            ->orderBy('transactions.id', 'desc')
            ->get();
    }
}
