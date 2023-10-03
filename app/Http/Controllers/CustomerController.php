<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $customer = Customer::query();
                return DataTables::of($customer)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $id = $data->id;
                        return '<a href="javascript:void(0)" id="show-modal-update" class="btn btn-sm btn-secondary mr-2" data-id="' . $id . '" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="bi bi-pencil"></i></a><a href="javascript:void(0)" id="btn-delete" class="btn btn-sm btn-danger" data-id="' . $id . '" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="bi bi-trash3"></i></a>';
                    })
                    ->toJson();
            }

            return view('customer.list');
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
            Customer::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Create data successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::find($id);
            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            unset($data['_token']);
            Customer::where('id', $id)
                ->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Update data successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            Customer::find($id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Delete data successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
