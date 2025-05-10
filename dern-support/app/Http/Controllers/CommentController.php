<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'support_request_id' => 'required|exists:support_requests,id',
            'content' => 'required|string|max:1000',
        ]);

        $supportRequest = SupportRequest::findOrFail($validated['support_request_id']);
        
        // Check if user has access to this support request
        if (!auth()->user()->is_staff && $supportRequest->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $comment = Comment::create([
            'support_request_id' => $validated['support_request_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content']
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        // Only allow users to delete their own comments
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}
