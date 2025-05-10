<?php

namespace App\Http\Controllers;

use App\Models\SatisfactionRating;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SatisfactionRatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'support_request_id' => 'required|exists:support_requests,id',
            'rating' => 'required|integer|between:1,5',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $supportRequest = SupportRequest::findOrFail($validated['support_request_id']);
        
        // Only allow rating if the request is completed and hasn't been rated
        if (!$supportRequest->canBeRated()) {
            return redirect()->back()->with('error', 'This support request cannot be rated.');
        }

        // Ensure only the request owner can rate it
        if ($supportRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $rating = SatisfactionRating::create($validated);

        return redirect()
            ->route('support-requests.show', $supportRequest)
            ->with('success', 'Thank you for your feedback!');
    }
}
