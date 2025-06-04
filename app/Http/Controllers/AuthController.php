<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ]);
        $user = User::create($validatedData);
        return response() - json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }
    public function index()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ]);
    }
    public function create()
    {
    }
    public function store(Request $request)
    {
    }
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    }
}
