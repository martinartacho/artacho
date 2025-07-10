<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email',
            'type' => 'nullable|string|max:50',
            'message' => 'required|string|min:5',
        ]);

        $feedback = Feedback::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'email' => $data['email'] ?? ($request->user()?->email ?? null),
            'type' => $data['type'] ?? 'general',
            'message' => $data['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback recibido correctamente',
            'data' => $feedback,
        ]);
    }
}
