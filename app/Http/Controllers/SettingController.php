<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.list');
    }

    public function account()
    {
        $account = User::find(1);
        $balance = number_format($account->balance);

        return response()->json([
            'success' => true,
            'data' => "<tr>
                            <th scope='row'>Name</th>
                            <td>:</td>
                            <td>$account->name</td>
                        </tr>
                        <tr>
                            <th scope='row'>Username</th>
                            <td>:</td>
                            <td>$account->username</td>
                        </tr>
                        <tr>
                            <th scope='row'>Email</th>
                            <td>:</td>
                            <td>$account->email</td>
                        </tr>
                        <tr>
                            <th scope='row'>Balance</th>
                            <td>:</td>
                            <td>$balance</td>
                        </tr>"
        ]);
    }

    public function updateSaldo(Request $request)
    {
        try {
            $user = User::find(1);
            $type = $request->type;
            $nominal = str_replace(',', '', $request->nominal);
            if ($type == 1) {
                $desc = 'Top Up Balance ' . number_format($nominal);
                $status = 2;
                $new_balance = $user->balance + $nominal;
            } else {
                $desc = 'Subsract Balance ' . number_format($nominal);
                $status = 3;
                $new_balance = $user->balance - $nominal;
            }
            DB::beginTransaction();
            Transaction::create([
                'balance' => $new_balance,
                'status' => $status,
                'description' => $desc
            ]);
            $user->update(['balance' => $new_balance]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Update data successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $user = User::find(1);
            $password = $request->password;

            $user->update(['password' => Hash::make($password)]);

            return response()->json([
                'success' => true,
                'message' => 'Reset password successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
