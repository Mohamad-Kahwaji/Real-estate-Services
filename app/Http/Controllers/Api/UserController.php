<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Returns a searchable list of all users with their business count and join date.
    public function index(Request $request)
    {
        $users = User::with('businesses')
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            }))
            ->latest()
            ->get()
            ->map(fn($user) => [
                'id'             => $user->id,
                'name'           => $user->name,
                'phone'          => $user->phone,
                'businesses_count' => $user->businesses->count(),
                'joined_at'      => $user->created_at?->format('Y-m-d'),
            ]);

        return response()->json([
            'status' => true,
            'total'  => $users->count(),
            'data'   => $users,
        ]);
    }

    // Returns a single user's profile along with their associated businesses and cities.
    public function show($id)
    {
        $user = User::with('businesses.city')->findOrFail($id);

        return response()->json([
            'status' => true,
            'data'   => [
                'id'         => $user->id,
                'name'       => $user->name,
                'phone'      => $user->phone,
                'joined_at'  => $user->created_at?->format('Y-m-d'),
                'businesses' => $user->businesses->map(fn($b) => [
                    'id'     => $b->id,
                    'name'   => $b->name,
                    'status' => $b->status,
                    'city'   => $b->city?->name ?? null,
                ]),
            ],
        ]);
    }
}
