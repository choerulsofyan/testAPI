<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
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

        $query = User::query();

        if ($search) {
            $query->where('email', 'like', '%' . $search . '%')->orWhere('role', 'like', '%' . $search . '%');
        }

        $query->take($take)->skip($skip);

        $users = $query->get();

        return response()->json([
            'code' => 200,
            'message' => 'Users retrieved successfully.',
            'data' => $users,
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
            'email' => 'required|string|unique:users',
            'password' => 'password',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ]);

        try {
            $user = User::create([
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'User created successfully.',
                'data' => $user,
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
            $user = User::findOrFail($id);

            return response()->json([
                'code' => 200,
                'message' => 'User retrieved successfully',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found.',
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
                'email' => 'required|unique:users,email,' . $id,
                'password' => 'password',
                'confirm_password' => 'required|same:password',
                'role' => 'required',
            ]);

            $user = User::findOrFail($id);

            $user->update([
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role,
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'User updated successfully.',
                'data' => $user,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation error.',
                'data' => $e->validator->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'An error occurred.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'code' => 200,
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found.',
            ], 404);
        }
    }
}
