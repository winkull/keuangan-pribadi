<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $product = Product::query();
                return DataTables::of($product)
                    ->addIndexColumn()
                    ->editColumn('selling_price', function ($data) {
                        return number_format($data->selling_price);
                    })
                    ->addColumn('action', function ($data) {
                        $id = $data->id;
                        return '<a href="javascript:void(0)" id="show-modal-update" class="btn btn-sm btn-secondary mr-2" data-id="' . $id . '" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="bi bi-pencil"></i></a><a href="javascript:void(0)" id="btn-delete" class="btn btn-sm btn-danger" data-id="' . $id . '" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="bi bi-trash3"></i></a>';
                    })->toJson();
            }
            return view('product.list');
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
            Product::create($request->all());
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
            $product = Product::find($id);
            $product->selling_price = number_format($product->selling_price);
            return response()->json([
                'success' => true,
                'data' => $product
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
            Product::where('id', $id)
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
            Product::find($id)->delete();
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
