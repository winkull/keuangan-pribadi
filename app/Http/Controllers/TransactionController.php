<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $transaction = Transaction::leftJoin('products', 'products.id', 'transactions.product_id')
                    ->leftJoin('customers', 'customers.id', 'transactions.customer_id')
                    ->selectRaw("
                        transactions.created_at,
                        products.name AS transaction,
                        customers.name AS customer,
                        transactions.purchase_price,
                        transactions.selling_price,
                        transactions.profit,
                        transactions.balance,
                        transactions.status,
                        transactions.description
                    ");

                return DataTables::of($transaction)
                    ->addIndexColumn()
                    ->editColumn('created_at', function ($item) {
                        return Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                    })
                    ->editColumn('purchase_price', function ($item) {
                        return number_format($item->purchase_price);
                    })
                    ->editColumn('selling_price', function ($item) {
                        return number_format($item->selling_price);
                    })
                    ->editColumn('profit', function ($item) {
                        return number_format($item->profit);
                    })
                    ->editColumn('balance', function ($item) {
                        return number_format($item->balance);
                    })
                    ->editColumn('transaction', function ($item) {
                        return $item->transaction ? $item->transaction : '-';
                    })
                    ->editColumn('customer', function ($item) {
                        return $item->customer ? $item->customer : '-';
                    })
                    ->editColumn('description', function ($item) {
                        return $item->description ? $item->description : '-';
                    })
                    ->editColumn('status', function ($item) {
                        return $item->status == 1 ? '<span class="badge badge-success">Lunas</span>' : ($item->status == 2 ? '<span class="badge badge-info">Top Up</span>' : ($item->status == 3 ? '<span class="badge badge-warning">Substract</span>' : '<span class="badge badge-danger">Hutang</span>'));
                    })
                    ->rawColumns(['status'])
                    ->escapeColumns(['status'])
                    ->toJson();
            }

            $datas['products'] = Product::all();
            $datas['customers'] = Customer::all();
            return view('transaction.list', $datas);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = User::find(1);

            $data = $request->all();
            unset($data['_token']);
            $data['balance'] = $user->balance - $request->purchase_price;

            if ($data['balance'] < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, the balance is insufficient, please top up again',
                ], 400);
            }

            DB::beginTransaction();
            $transaction = Transaction::create($data);
            $user->update([
                'balance' => $transaction->balance
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Create data successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
