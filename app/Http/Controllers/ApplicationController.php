<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    // View all applications
    public function viewAll()
    {
        $applications = Application::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'ok' => true,
            'data' => $applications
        ], 200);
    }

    // Create application (from website form)
    public function create(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'message' => 'required|string',
            'phone' => 'required|string|max:20',
            'course' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $application = Application::create($request->all());

        return response()->json([
            'ok' => true,
            'message' => 'Application submitted successfully',
            'data' => $application,
        ], 201);
    }

    // Delete application
    public function delete($application_id)
    {
        $application = Application::find($application_id);

        if (!$application) {
            return response()->json([
                'ok' => false,
                'message' => 'Application not found'
            ], 404);
        }

        $application->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Application deleted successfully',
        ], 200);
    }
} 