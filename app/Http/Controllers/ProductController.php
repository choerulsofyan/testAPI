<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $take = $request->query('take', 10);
        $skip = $request->query('skip', 0);
        $search = $request->query('search', '');

        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $query->take($take)->skip($skip);

        $products = $query->get();

        return response()->json([
            'code' => 200,
            'message' => 'Products retrieved successfully.',
            'data' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:products',
            'qty' => 'required|integer',
        ]);

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'qty' => $request->qty,
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'Product created successfully.',
                'data' => $product,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation error.',
                'errors' => $e->validator->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'code' => 200,
                'message' => 'Product retrieved successfully',
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 404,
                'message' => 'Product not found.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:products,name,' . $id,
                'qty' => 'required|integer',
            ]);

            $product = Product::findOrFail($id);

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'qty' => $request->qty,
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Product updated successfully.',
                'data' => $product,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation error.',
                'errors' => $e->validator->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'code' => 200,
                'message' => 'Product deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 404,
                'message' => 'Product not found.',
            ], 404);
        }
    }
}
