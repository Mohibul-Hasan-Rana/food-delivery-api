<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryAssignmentController extends Controller
{
    private DeliveryAssignmentService $assignmentService;

    public function __construct(DeliveryAssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    public function respond(Request $request, DeliveryAssignment $assignment): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $success = $this->assignmentService->respondToAssignment($assignment, $request->status);

        if (!$success) {
            return response()->json(['message' => 'Invalid status'], 422);
        }

        return response()->json([
            'message' => 'Assignment response recorded successfully'
        ]);
    }
}
