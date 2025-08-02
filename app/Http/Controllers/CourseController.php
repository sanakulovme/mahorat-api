<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'level' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/courses', $imageName);
            $data['image'] = 'storage/courses/' . $imageName;
        }

        $course = Course::create($data);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'level' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
        ]);

        $course = Course::find($request->id);
        $data = $request->except(['id', 'image']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($course->image && Storage::exists('public/' . str_replace('storage/', '', $course->image))) {
                Storage::delete('public/' . str_replace('storage/', '', $course->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/courses', $imageName);
            $data['image'] = 'storage/courses/' . $imageName;
        }

        $course->update($data);

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

        // Delete associated image
        if ($course->image && Storage::exists('public/' . str_replace('storage/', '', $course->image))) {
            Storage::delete('public/' . str_replace('storage/', '', $course->image));
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
