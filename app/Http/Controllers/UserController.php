<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserNotification;
use App\Mail\UserConfirmation;
class UserController extends Controller
{
    // POST /api/users
    public function store(Request $request)
{
    try {
        // Validate input
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|string|min:3|max:50',
        ]);

        // Create user
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'name' => $validated['name'],
        ]);

        Mail::to($user->email)->send(new UserConfirmation($user));
        Mail::to("admin@gmail.com")->send(new NewUserNotification($user));

        // Return success response
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'created_at' => $user->created_at,
            ]
        ], 201);

    } catch (ValidationException $e) {
        // Handle validation errors
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);

    } catch (QueryException $e) {
        // Handle database errors
        return response()->json([
            'status' => 'error',
            'message' => 'Database error occurred',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);

    } catch (\Exception $e) {
        // Handle other unexpected errors
        return response()->json([
            'status' => 'error',
            'message' => 'An unexpected error occurred',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);
    }
}

    // GET /api/users
    public function index(Request $request)
{
    try {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $sortBy = $request->input('sortBy', 'created_at');

        $query = User::where('active', true);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        // Retrieve paginated results with sorting
        $users = $query->withCount('orders')
                       ->orderBy($sortBy)
                       ->paginate(10, ['id', 'email', 'name', 'created_at'], 'page', $page);

        return response()->json([
            'page' => $page,
            'users' => $users->items(),
        ]);

    } catch (QueryException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database error occurred',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An unexpected error occurred',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);
    }
}
}