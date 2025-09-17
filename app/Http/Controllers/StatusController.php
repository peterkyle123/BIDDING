<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidding;

class StatusController extends Controller
{
    /**
     * Update the status of a bidding (AJAX / POST)
     */
    public function update(Request $request, Bidding $bidding)
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', Bidding::STATUSES),
        ]);

        $newStatus = $request->input('status');

        // Ask the model what transitions are allowed
        $allowed = $bidding->allowedStatusTransitions();

        if (!in_array($newStatus, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => "Invalid transition from '{$bidding->status}' to '{$newStatus}'.",
                'allowed' => $allowed,
            ], 422);
        }

        $bidding->status = $newStatus;
        $bidding->save();

        return response()->json([
            'success' => true,
            'status'  => $bidding->status,
        ]);
    }
}
