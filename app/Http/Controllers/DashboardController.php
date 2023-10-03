<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['saldo'] = number_format(User::find(1)->balance);
        $data['profit_today'] = number_format($this->query()->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->where('transactions.status', 1)->sum('transactions.profit'));
        $data['transaction_today'] = number_format($this->query()->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))->count());
        $data['transaction_lunas'] = number_format($this->query()->where('transactions.status', 1)->sum('transactions.selling_price'));
        $data['transaction_hutang'] = number_format($this->query()->where('transactions.status', 0)->sum('transactions.selling_price'));
        $data['total_customer'] = number_format(Customer::count());
        $data['daftar_hutang'] = $this->daftarHutang();
        return view('dashboard.dashboard', $data);
    }

    public function daftarHutang()
    {
        $data = $this->query()->where('transactions.status', 0)->get()->toArray();
        $data = collect($data)->groupBy('id')->map(function ($item) {
            return [
                "name" => $item->first()['name'],
                "selling_price" => $item->sum('selling_price')
            ];
        });
        return $data;
    }

    public function query()
    {
        return Transaction::leftJoin('products', 'products.id', 'transactions.product_id')
            ->leftJoin('customers', 'customers.id', 'transactions.customer_id')
            ->selectRaw("
                customers.id,
                customers.name,
                transactions.purchase_price,
                transactions.selling_price,
                transactions.profit,
                transactions.status,
                transactions.created_at
            ");
    }
}