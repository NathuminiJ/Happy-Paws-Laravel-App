<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of all customer feedback.
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 10;
        $search = $request->get('search');
        $status = $request->get('status');
        $type = $request->get('type');

        // Get feedback with filters
        if ($search) {
            $feedbacks = Feedback::search($search, $perPage, $page);
        } else {
            $feedbacks = Feedback::paginate($perPage, $page);
        }

        return view('admin.feedback.index', compact('feedbacks', 'search', 'status', 'type'));
    }

    /**
     * Display the specified feedback.
     */
    public function show($id)
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            abort(404, 'Feedback not found.');
        }

        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified feedback.
     */
    public function edit($id)
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            abort(404, 'Feedback not found.');
        }

        return view('admin.feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified feedback.
     */
    public function update(Request $request, $id)
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            abort(404, 'Feedback not found.');
        }

        $request->validate([
            'status' => 'required|in:pending,read,responded,closed',
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $data = [
            'status' => $request->status,
            'admin_response' => $request->admin_response,
            'admin_id' => auth()->id(),
            'updated_at' => now()->toDateTimeString(),
        ];

        // If admin is responding, set responded_at
        if ($request->admin_response) {
            $data['responded_at'] = now()->toDateTimeString();
        }

        $feedback->update($data);

        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback updated successfully!');
    }

    /**
     * Remove the specified feedback.
     */
    public function destroy($id)
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            abort(404, 'Feedback not found.');
        }

        $feedback->delete();

        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback deleted successfully!');
    }

    /**
     * Get feedback statistics for admin dashboard.
     */
    public function stats()
    {
        $stats = Feedback::stats();
        return response()->json($stats);
    }
}
