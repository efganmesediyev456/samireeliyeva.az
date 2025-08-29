<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteLike;

class WebsiteLikeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'nullable|string|max:1000',
        ]);

        $like = WebsiteLike::create($validated);

        return response()->json([
            'message' => 'Your feedback has been saved successfully.',
            'data' => $like
        ], 201);
    }
}
