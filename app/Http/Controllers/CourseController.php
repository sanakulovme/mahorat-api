<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // View all courses
    public function viewAll()
    {
        $courses = Course::all();

        return response()->json([
            'ok' => true,
            'data' => $courses,
        ], 200);
    }

    // View single course
    public function view($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'ok' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $course,
        ], 200);
    }

    // Create course
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'url' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
        ]);

        $course = Course::create($request->all());

        return response()->json([
            'ok' => true,
            'message' => 'Course created successfully',
            'data' => $course,
        ], 201);
    }

    // Update course
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:courses,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'url' => 'required|string|max:255',
            'level' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
        ]);

        $course = Course::find($request->id);
        $course->update($request->except('id'));

        return response()->json([
            'ok' => true,
            'message' => 'Course updated successfully',
            'data' => $course,
        ], 200);
    }

    // Delete course
    public function delete($course_id)
    {
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json([
                'ok' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Course deleted successfully',
        ], 200);
    }

    // Legacy method for backward compatibility
    public function showAll()
    {
        return $this->viewAll();
    }
}
